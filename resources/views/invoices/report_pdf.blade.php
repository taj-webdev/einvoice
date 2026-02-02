<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        /* ================= HEADER ================= */
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
        }

        .company-info {
            text-align: center;
        }

        .company-info h2 {
            margin: 0;
            font-size: 18px;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #374151;
        }

        /* ================= TITLE ================= */
        .report-title {
            text-align: center;
            margin: 20px 0 10px;
        }

        .report-title h3 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .filter-info {
            margin-bottom: 12px;
            font-size: 11px;
        }

        /* ================= TABLE ================= */
        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.report th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
            text-align: center;
        }

        table.report td {
            border: 1px solid #e5e7eb;
            padding: 7px;
            font-size: 11px;
        }

        table.report tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* ================= BADGE ================= */
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            color: #fff;
            font-weight: bold;
            display: inline-block;
        }

        .paid {
            background-color: #16a34a;
        }

        .unpaid {
            background-color: #dc2626;
        }

        /* ================= FOOTER ================= */
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #374151;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="20%">
                </td>
                <td width="60%" class="company-info">
                    <h2>{{ $company->company_name ?? '-' }}</h2>
                    <p>{{ $company->company_address ?? '-' }}</p>
                    <p>
                        Telp: {{ $company->company_phone ?? '-' }} |
                        Email: {{ $company->company_email ?? '-' }}
                    </p>
                </td>
                <td width="20%"></td>
            </tr>
        </table>
    </div>

    <!-- ================= TITLE ================= -->
    <div class="report-title">
        <h3>Laporan Invoice</h3>
    </div>

    <div class="filter-info">
        <strong>Periode:</strong>
        {{ $dateFrom ?? '-' }} s/d {{ $dateTo ?? '-' }} <br>
        <strong>Status:</strong> {{ $status ?? 'SEMUA' }}
    </div>

    <!-- ================= TABLE ================= -->
    <table class="report">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">No Invoice</th>
                <th width="25%">Customer</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Status</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $i => $inv)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ $inv->invoice_number }}</td>
                <td>{{ $inv->customer_name }}</td>
                <td class="text-center">{{ $inv->invoice_date }}</td>
                <td class="text-center">
                    <span class="badge {{ $inv->status === 'PAID' ? 'paid' : 'unpaid' }}">
                        {{ $inv->status }}
                    </span>
                </td>
                <td class="text-right">
                    Rp {{ number_format($inv->grand_total, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        Dicetak pada :
        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }} WIB
    </div>

</body>
</html>
