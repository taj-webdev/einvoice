<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\InvoiceExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /* ================= LIST ================= */
    public function index(Request $request)
    {
        $search     = $request->search;
        $dateFrom   = $request->date_from;
        $dateTo     = $request->date_to;
        $status     = $request->status;

        $invoices = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->select('invoices.*', 'customers.customer_name')

            // 🔍 SEARCH
            ->when($search, function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customers.customer_name', 'like', "%{$search}%");
            })

            // 📅 FILTER TANGGAL
            ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('invoice_date', [$dateFrom, $dateTo]);
            })

            // 💰 FILTER STATUS
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })

            ->orderByDesc('invoices.id')
            ->paginate(10)
            ->withQueryString(); // 🔥 PENTING

        return view('invoices.index', compact(
            'invoices',
            'search',
            'dateFrom',
            'dateTo',
            'status'
        ));
    }

    /* ================= EXPORT PDF ================= */
    public function reportPdf(Request $request)
    {
        $search   = $request->search;
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;
        $status   = $request->status;

        $invoices = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->select('invoices.*', 'customers.customer_name')
            ->when($search, fn($q) =>
                $q->where('invoice_number', 'like', "%$search%")
                  ->orWhere('customers.customer_name', 'like', "%$search%")
            )
            ->when($dateFrom && $dateTo, fn($q) =>
                $q->whereBetween('invoice_date', [$dateFrom, $dateTo])
            )
            ->when($status, fn($q) =>
                $q->where('status', $status)
            )
            ->orderBy('invoice_date')
            ->get();

        $company = DB::table('company_settings')->first();

        $pdf = Pdf::loadView('invoices.report_pdf', compact(
            'invoices',
            'company',
            'dateFrom',
            'dateTo',
            'status'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-Invoice.pdf');
    }

    /* ================= EXPORT EXCEL ================= */
    public function reportExcel(Request $request)
    {
        return Excel::download(
            new InvoiceExport($request->all()),
            'Laporan-Invoice.xlsx'
        );
    }

    /* ================= CREATE ================= */
    public function create()
    {
        $customers = DB::table('customers')
            ->where('is_active', 1)
            ->orderBy('customer_name')
            ->get();

        return view('invoices.create', compact('customers'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if (!$request->has('items') || count($request->items) === 0) {
                return back()->with('error', 'Item invoice tidak boleh kosong');
            }

            $invoiceNumber = $this->generateInvoiceNumber();

            $invoiceId = DB::table('invoices')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $request->customer_id,
                'invoice_date'   => $request->invoice_date,
                'due_date'       => $request->due_date,
                'notes'          => $request->notes,
                'subtotal'       => (float) $request->subtotal,
                'tax_ppn'        => (float) $request->tax_ppn,
                'grand_total'    => (float) $request->grand_total,
                'status'         => 'UNPAID',
                'created_by'     => Session::get('user_id'),
                'created_at'     => now()
            ]);

            foreach ($request->items as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id'       => $invoiceId,
                    'item_description' => $item['description'],
                    'quantity'         => (float) $item['qty'],
                    'unit'             => $item['unit'],
                    'price'            => (float) $item['price'],
                    'total_price'      => (float) $item['total'],
                    'created_at'       => now()
                ]);
            }

            DB::commit();
            return redirect('/invoices')->with('success', 'Invoice berhasil ditambahkan');

        } catch (\Throwable $e) {
            DB::rollBack();
            dd('STORE ERROR', $e->getMessage(), $e->getLine());
        }
    }

    /* ================= EDIT (LOCK PAID) ================= */
    public function edit($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();

        if (!$invoice) abort(404);

        if ($invoice->status === 'PAID') {
            return redirect('/invoices')
                ->with('error', 'Invoice PAID tidak dapat diedit');
        }

        $items = DB::table('invoice_items')
            ->where('invoice_id', $id)
            ->get();

        return view('invoices.edit', compact('invoice', 'items'));
    }

    /* ================= UPDATE (DOUBLE LOCK) ================= */
    public function update(Request $request, $id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();

        if (!$invoice) abort(404);

        if ($invoice->status === 'PAID') {
            return redirect('/invoices')
                ->with('error', 'Invoice PAID tidak dapat diubah');
        }

        DB::beginTransaction();

        try {
            if (!$request->has('items') || count($request->items) === 0) {
                return back()->with('error', 'Item invoice tidak boleh kosong');
            }

            DB::table('invoices')->where('id', $id)->update([
                'invoice_date' => $request->invoice_date,
                'due_date'     => $request->due_date,
                'notes'        => $request->notes,
                'subtotal'     => (float) $request->subtotal,
                'tax_ppn'      => (float) $request->tax_ppn,
                'grand_total'  => (float) $request->grand_total,
                'updated_at'   => now()
            ]);

            DB::table('invoice_items')->where('invoice_id', $id)->delete();

            foreach ($request->items as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id'       => $id,
                    'item_description' => $item['description'],
                    'quantity'         => (float) $item['qty'],
                    'unit'             => $item['unit'],
                    'price'            => (float) $item['price'],
                    'total_price'      => (float) $item['total'],
                    'created_at'       => now()
                ]);
            }

            DB::commit();
            return redirect('/invoices')->with('success', 'Invoice berhasil diupdate');

        } catch (\Throwable $e) {
            DB::rollBack();
            dd('UPDATE ERROR', $e->getMessage(), $e->getLine());
        }
    }

    /* ================= SHOW ================= */
    public function show($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();
        $items   = DB::table('invoice_items')->where('invoice_id', $id)->get();

        if (!$invoice) abort(404);

        return view('invoices.show', compact('invoice', 'items'));
    }

    /* ================= DELETE (LOCK PAID) ================= */
    public function destroy($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();

        if (!$invoice) abort(404);

        if ($invoice->status === 'PAID') {
            return redirect('/invoices')
                ->with('error', 'Invoice PAID tidak dapat dihapus');
        }

        DB::table('invoice_items')->where('invoice_id', $id)->delete();
        DB::table('invoices')->where('id', $id)->delete();

        return redirect('/invoices')->with('success', 'Invoice berhasil dihapus');
    }

    /* ================= TOGGLE STATUS ================= */
    public function toggleStatus($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();
        if (!$invoice) abort(404);

        $newStatus = $invoice->status === 'PAID' ? 'UNPAID' : 'PAID';

        DB::table('invoices')->where('id', $id)->update([
            'status'     => $newStatus,
            'updated_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Status invoice berhasil diubah menjadi ' . $newStatus);
    }

    /* ================= PRINT PDF ================= */
    public function printInvoice($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();
        if (!$invoice) abort(404);

        // 🔒 LOCK: hanya PAID boleh cetak
        if ($invoice->status !== 'PAID') {
            return redirect('/invoices')
                ->with('error', 'Invoice harus PAID sebelum dicetak');
        }

        $items   = DB::table('invoice_items')->where('invoice_id', $id)->get();
        $company = DB::table('company_settings')->first();

        $safeInvoiceNumber = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        $pdf = Pdf::loadView('invoices.print', compact(
            'invoice',
            'items',
            'company'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Invoice-' . $safeInvoiceNumber . '.pdf');
    }

    /* ================= PRINT SURAT JALAN ================= */
    public function printSuratJalan($id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->first();
        if (!$invoice) abort(404);

        $items   = DB::table('invoice_items')->where('invoice_id', $id)->get();
        $company = DB::table('company_settings')->first();

        // Amankan nama file
        $safeInvoiceNumber = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        $pdf = Pdf::loadView('invoices.surat_jalan', compact(
            'invoice',
            'items',
            'company'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Surat-Jalan-' . $safeInvoiceNumber . '.pdf');
    }
    
    /* ================= AUTO NUMBER ================= */
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $monthRoman = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n') - 1];

        $counter = DB::table('invoice_counters')->where('year', $year)->first();

        if (!$counter) {
            DB::table('invoice_counters')->insert([
                'year' => $year,
                'last_number' => 0
            ]);
            $counter = DB::table('invoice_counters')->where('year', $year)->first();
        }

        $next = $counter->last_number + 1;

        DB::table('invoice_counters')
            ->where('year', $year)
            ->update(['last_number' => $next]);

        return str_pad($next, 4, '0', STR_PAD_LEFT)
            . '/TAJWDV/' . $monthRoman . '/' . $year;
    }
}
