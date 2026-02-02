<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /* ================= TIMEZONE ================= */
        $now = Carbon::now('Asia/Jakarta');

        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd   = $now->copy()->endOfMonth();

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd   = $now->copy()->subMonth()->endOfMonth();

        /* ================= BASE QUERY ================= */
        $thisMonth = DB::table('invoices')
            ->whereBetween('invoice_date', [$thisMonthStart, $thisMonthEnd]);

        $lastMonth = DB::table('invoices')
            ->whereBetween('invoice_date', [$lastMonthStart, $lastMonthEnd]);

        /* ================= TOTAL NOMINAL ================= */
        $totalInvoice = (clone $thisMonth)->sum('grand_total');
        $totalPaid    = (clone $thisMonth)->where('status','PAID')->sum('grand_total');
        $totalUnpaid  = (clone $thisMonth)->where('status','UNPAID')->sum('grand_total');

        $lastTotalInvoice = (clone $lastMonth)->sum('grand_total');
        $lastTotalPaid    = (clone $lastMonth)->where('status','PAID')->sum('grand_total');
        $lastTotalUnpaid  = (clone $lastMonth)->where('status','UNPAID')->sum('grand_total');

        /* ================= TOTAL COUNT ================= */
        $countInvoice = (clone $thisMonth)->count();
        $countPaid    = (clone $thisMonth)->where('status','PAID')->count();
        $countUnpaid  = (clone $thisMonth)->where('status','UNPAID')->count();

        $lastCountInvoice = (clone $lastMonth)->count();
        $lastCountPaid    = (clone $lastMonth)->where('status','PAID')->count();
        $lastCountUnpaid  = (clone $lastMonth)->where('status','UNPAID')->count();

        /* ================= PERCENT HELPER ================= */
        $percent = fn($now,$prev) =>
            $prev > 0 ? round((($now - $prev) / $prev) * 100, 1) : 0;

        /* ================= PERCENTAGE ================= */
        $percentTotalInvoice = $percent($totalInvoice, $lastTotalInvoice);
        $percentTotalPaid    = $percent($totalPaid, $lastTotalPaid);
        $percentTotalUnpaid  = $percent($totalUnpaid, $lastTotalUnpaid);

        $percentCountInvoice = $percent($countInvoice, $lastCountInvoice);
        $percentCountPaid    = $percent($countPaid, $lastCountPaid);
        $percentCountUnpaid  = $percent($countUnpaid, $lastCountUnpaid);

        /* ================= MONTHLY DATA ================= */
        $monthly = DB::table('invoices')
            ->selectRaw('
                MONTH(invoice_date) as month,
                SUM(grand_total) as total,
                SUM(CASE WHEN status="PAID" THEN grand_total ELSE 0 END) as paid,
                SUM(CASE WHEN status="UNPAID" THEN grand_total ELSE 0 END) as unpaid,
                COUNT(*) as jumlah,
                SUM(CASE WHEN status="PAID" THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN status="UNPAID" THEN 1 ELSE 0 END) as unpaid_count
            ')
            ->whereYear('invoice_date', $now->year)
            ->groupByRaw('MONTH(invoice_date)')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', compact(
            'totalInvoice','totalPaid','totalUnpaid',
            'countInvoice','countPaid','countUnpaid',
            'percentTotalInvoice','percentTotalPaid','percentTotalUnpaid',
            'percentCountInvoice','percentCountPaid','percentCountUnpaid',
            'monthly'
        ));
    }
}
