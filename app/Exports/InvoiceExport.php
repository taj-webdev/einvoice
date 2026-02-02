<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InvoiceExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    ShouldAutoSize
{
    protected $filters;
    protected $company;
    protected $rowCount;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->company = DB::table('company_settings')->first();
    }

    /* ================= DATA ================= */
    public function collection()
    {
        $data = DB::table('invoices')
            ->join('customers','customers.id','=','invoices.customer_id')
            ->select(
                'invoice_number',
                'customer_name',
                'invoice_date',
                'status',
                'grand_total'
            )
            ->when($this->filters['date_from'] && $this->filters['date_to'], fn($q)=>
                $q->whereBetween('invoice_date', [
                    $this->filters['date_from'],
                    $this->filters['date_to']
                ])
            )
            ->when($this->filters['status'], fn($q)=>
                $q->where('status', $this->filters['status'])
            )
            ->orderBy('invoice_date')
            ->get();

        $this->rowCount = $data->count();

        return $data;
    }

    /* ================= HEADINGS ================= */
    public function headings(): array
    {
        return [
            'NO INVOICE',
            'CUSTOMER',
            'TANGGAL',
            'STATUS',
            'TOTAL (Rp)'
        ];
    }

    /* ================= BASIC STYLES ================= */
    public function styles(Worksheet $sheet)
    {
        // Header table
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        return [];
    }

    /* ================= EVENTS ================= */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /* ===== HEADER COMPANY ===== */
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');

                $sheet->setCellValue('A1', strtoupper($this->company->company_name ?? ''));
                $sheet->setCellValue('A2', $this->company->company_address ?? '');
                $sheet->setCellValue(
                    'A3',
                    'Telp: ' . ($this->company->company_phone ?? '-') .
                    ' | Email: ' . ($this->company->company_email ?? '-')
                );

                $sheet->getStyle('A1:A3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ]);

                /* ===== TITLE ===== */
                $sheet->mergeCells('A4:E4');
                $sheet->setCellValue('A4', 'LAPORAN INVOICE');

                $sheet->getStyle('A4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                /* ===== TABLE BORDER ===== */
                $lastRow = 5 + $this->rowCount;

                $sheet->getStyle("A5:E{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ===== STATUS COLOR ===== */
                for ($row = 6; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell("D{$row}")->getValue();

                    if ($status === 'PAID') {
                        $sheet->getStyle("D{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('16A34A'); // green
                    } else {
                        $sheet->getStyle("D{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('DC2626'); // red
                    }
                }

                /* ===== FOOTER ===== */
                $footerRow = $lastRow + 2;
                $sheet->mergeCells("C{$footerRow}:E{$footerRow}");

                $sheet->setCellValue(
                    "C{$footerRow}",
                    'Dicetak Pada : ' .
                    Carbon::now('Asia/Jakarta')
                        ->translatedFormat('l, d F Y H:i') . ' WIB'
                );

                $sheet->getStyle("C{$footerRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ],
                    'font' => [
                        'italic' => true,
                        'size' => 10
                    ]
                ]);
            }
        ];
    }
}
