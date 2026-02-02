<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Invoice | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fade-in {
            animation: fadeInUp .8s cubic-bezier(.4,0,.2,1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(30px); }
            to   { opacity:1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex">

@include('layouts.sidebar_admin')

<div class="flex-1 min-h-screen flex flex-col">
@include('layouts.header_admin')

<main class="p-6 max-w-6xl mx-auto fade-in space-y-6">

@php
    $company = DB::table('company_settings')->first();

    function terbilang($angka) {
        $angka = abs($angka);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        if ($angka < 12) return $huruf[$angka];
        elseif ($angka < 20) return terbilang($angka - 10) . " Belas";
        elseif ($angka < 100) return terbilang(intval($angka / 10)) . " Puluh " . terbilang($angka % 10);
        elseif ($angka < 200) return "Seratus " . terbilang($angka - 100);
        elseif ($angka < 1000) return terbilang(intval($angka / 100)) . " Ratus " . terbilang($angka % 100);
        elseif ($angka < 2000) return "Seribu " . terbilang($angka - 1000);
        elseif ($angka < 1000000) return terbilang(intval($angka / 1000)) . " Ribu " . terbilang($angka % 1000);
        elseif ($angka < 1000000000) return terbilang(intval($angka / 1000000)) . " Juta " . terbilang($angka % 1000000);
        else return "Jumlah terlalu besar";
    }
@endphp

<!-- ================= COMPANY HEADER ================= -->
<div class="bg-white p-6 rounded-2xl shadow flex items-center gap-6">
    @if($company && $company->company_logo)
        <img src="{{ asset($company->company_logo) }}"
             class="h-20 rounded-lg shadow">
    @endif

    <div>
        <h2 class="text-xl font-semibold">{{ $company->company_name ?? '-' }}</h2>
        <p class="text-sm text-gray-600">{{ $company->company_address ?? '-' }}</p>
        <p class="text-sm text-gray-600">
            {{ $company->company_phone }} | {{ $company->company_email }}
        </p>
    </div>
</div>

<!-- ================= INVOICE INFO ================= -->
<div class="bg-white p-6 rounded-2xl shadow">
    <h1 class="text-2xl font-bold mb-4 flex items-center gap-2">
        <i data-lucide="file-text" class="text-indigo-600"></i>
        Invoice {{ $invoice->invoice_number }}
    </h1>

    <table class="w-full text-sm">
        <tr>
            <td class="font-semibold w-48">Tanggal Invoice</td>
            <td>{{ $invoice->invoice_date }}</td>
        </tr>
        <tr>
            <td class="font-semibold">Due Date</td>
            <td>{{ $invoice->due_date }}</td>
        </tr>
        <tr>
            <td class="font-semibold">Status</td>
            <td>
                <span class="px-3 py-1 rounded text-white
                {{ $invoice->status === 'PAID' ? 'bg-emerald-600' : 'bg-red-600' }}">
                    {{ $invoice->status }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="font-semibold">Catatan</td>
            <td>{{ $invoice->notes ?: '-' }}</td>
        </tr>
    </table>
</div>

<!-- ================= ITEMS ================= -->
<div class="bg-white p-6 rounded-2xl shadow">
    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Deskripsi</th>
                <th class="p-2 w-20">Qty</th>
                <th class="p-2 w-24">Unit</th>
                <th class="p-2 w-32">Harga</th>
                <th class="p-2 w-32">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr class="border-t">
                <td class="p-2">{{ $item->item_description }}</td>
                <td class="p-2 text-center">{{ $item->quantity }}</td>
                <td class="p-2 text-center">{{ $item->unit }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
                <td class="p-2 text-right">Rp {{ number_format($item->total_price,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="mt-4 flex justify-end">
        <table class="text-sm">
            <tr>
                <td class="pr-4">Subtotal</td>
                <td class="text-right">Rp {{ number_format($invoice->subtotal,0,',','.') }}</td>
            </tr>
            <tr>
                <td class="pr-4">PPN 11%</td>
                <td class="text-right">Rp {{ number_format($invoice->tax_ppn,0,',','.') }}</td>
            </tr>
            <tr class="font-bold">
                <td class="pr-4">Grand Total</td>
                <td class="text-right">Rp {{ number_format($invoice->grand_total,0,',','.') }}</td>
            </tr>
        </table>
    </div>

    <!-- TERBILANG -->
    <p class="mt-4 italic text-gray-600">
        Terbilang :
        <strong>{{ terbilang((int)$invoice->grand_total) }} Rupiah</strong>
    </p>
</div>

<!-- ================= ACTION BUTTON ================= -->
<div class="flex flex-wrap gap-3 justify-end">

    <a href="/invoices"
       class="px-5 py-2 border rounded-xl flex items-center gap-2">
        <i data-lucide="arrow-left"></i> Kembali
    </a>

    <!-- EDIT (LOCK JIKA PAID) -->
    @if($invoice->status === 'UNPAID')
        <a href="/invoices/edit/{{ $invoice->id }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-xl flex items-center gap-2">
            <i data-lucide="edit"></i> Edit
        </a>
    @else
        <button disabled
            class="px-5 py-2 bg-gray-400 text-white rounded-xl flex items-center gap-2 cursor-not-allowed">
            <i data-lucide="lock"></i> Edit Locked
        </button>
    @endif

    <!-- TOGGLE STATUS -->
    <a href="/invoices/status/{{ $invoice->id }}"
       class="px-5 py-2 rounded-xl flex items-center gap-2
       {{ $invoice->status === 'PAID'
            ? 'bg-red-600 text-white'
            : 'bg-emerald-600 text-white' }}">
        <i data-lucide="{{ $invoice->status === 'PAID' ? 'x-circle' : 'check-circle' }}"></i>
        {{ $invoice->status === 'PAID' ? 'Set UNPAID' : 'Set PAID' }}
    </a>

    @if($invoice->status === 'PAID')
        <a href="/invoices/print/{{ $invoice->id }}"
           class="px-5 py-2 bg-indigo-600 text-white rounded-xl
                  flex items-center gap-2">
            <i data-lucide="printer"></i>
            Cetak Invoice
        </a>
    @else
        <button
            onclick="alert('Invoice harus PAID sebelum dicetak')"
            class="px-5 py-2 bg-gray-400 text-white rounded-xl
                   flex items-center gap-2 cursor-not-allowed">
            <i data-lucide="lock"></i>
            Cetak Invoice (LOCK)
        </button>
    @endif

    <a href="/invoices/surat-jalan/{{ $invoice->id }}"
       class="px-5 py-2 bg-gray-700 text-white rounded-xl flex items-center gap-2">
        <i data-lucide="truck"></i> Cetak Surat Jalan
    </a>

</div>

</main>

@include('layouts.footer_admin')
</div>
</div>

<script>
    lucide.createIcons();
</script>

<!-- ================= SWEET ALERT ================= -->
@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}'
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

</body>
</html>
