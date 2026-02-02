<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice {{ $invoice->invoice_number }}</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #333;
    }

    .header {
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: table;
        width: 100%;
    }

    .header-left {
        width: 20%;
        display: table-cell;
        vertical-align: middle;
    }

    .header-center {
        width: 80%;
        display: table-cell;
        text-align: center;
        vertical-align: middle;
    }

    .header img {
        height: 70px;
    }

    h1 {
        margin: 0;
        font-size: 20px;
    }

    .info-table td {
        padding: 4px 0;
    }

    .items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .items th, .items td {
        border: 1px solid #000;
        padding: 6px;
    }

    .items th {
        background: #f2f2f2;
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .watermark {
        position: fixed;
        top: 45%;
        left: 15%;
        font-size: 100px;
        color: rgba(200,0,0,.15);
        transform: rotate(-30deg);
        z-index: -1;
    }

    .footer {
        position: fixed;
        bottom: 20px;
        right: 0;
        font-size: 10px;
        text-align: right;
    }

    .signature {
        margin-top: 40px;
        width: 100%;
    }

    .signature td {
        height: 80px;
        vertical-align: bottom;
        text-align: center;
    }
</style>
</head>

<body>

@if($invoice->status === 'PAID')
<div class="watermark">PAID</div>
@endif

<!-- ================= HEADER ================= -->
<div class="header">
    <div class="header-left">
        @if($company && $company->company_logo)
            <img src="{{ public_path($company->company_logo) }}">
        @endif
    </div>
    <div class="header-center">
        <h1>{{ $company->company_name }}</h1>
        <div>{{ $company->company_address }}</div>
        <div>
            {{ $company->company_phone }} | {{ $company->company_email }}
        </div>
    </div>
</div>

<!-- ================= INFO ================= -->
<table class="info-table" width="100%">
<tr>
    <td width="15%">Invoice No</td>
    <td width="35%">: {{ $invoice->invoice_number }}</td>
    <td width="15%">Tanggal</td>
    <td width="35%">: {{ $invoice->invoice_date }}</td>
</tr>
<tr>
    <td>Due Date</td>
    <td>: {{ $invoice->due_date }}</td>
    <td>Status</td>
    <td>: {{ $invoice->status }}</td>
</tr>
</table>

<!-- ================= ITEMS ================= -->
<table class="items">
<thead>
<tr>
    <th>No</th>
    <th>Deskripsi</th>
    <th>Qty</th>
    <th>Unit</th>
    <th>Harga</th>
    <th>Total</th>
</tr>
</thead>
<tbody>
@foreach($items as $i => $item)
<tr>
    <td align="center">{{ $i+1 }}</td>
    <td>{{ $item->item_description }}</td>
    <td align="center">{{ $item->quantity }}</td>
    <td align="center">{{ $item->unit }}</td>
    <td class="right">Rp {{ number_format($item->price,0,',','.') }}</td>
    <td class="right">Rp {{ number_format($item->total_price,0,',','.') }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- ================= TOTAL ================= -->
<table width="100%" style="margin-top:10px">
<tr>
    <td width="70%"></td>
    <td width="30%">
        <table width="100%">
            <tr>
                <td>Subtotal</td>
                <td class="right">Rp {{ number_format($invoice->subtotal,0,',','.') }}</td>
            </tr>
            <tr>
                <td>PPN 11%</td>
                <td class="right">Rp {{ number_format($invoice->tax_ppn,0,',','.') }}</td>
            </tr>
            <tr>
                <td><strong>Grand Total</strong></td>
                <td class="right"><strong>Rp {{ number_format($invoice->grand_total,0,',','.') }}</strong></td>
            </tr>
        </table>
    </td>
</tr>
</table>

<!-- ================= SIGNATURE ================= -->
<table class="signature">
<tr>
    <td width="70%"></td>
    <td width="30%">
        Disetujui Oleh :
        <br><br><br>
        ( ____________________ )
    </td>
</tr>
</table>

<!-- ================= FOOTER ================= -->
<div class="footer">
    Dicetak Pada :
    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }} WIB
</div>

</body>
</html>
