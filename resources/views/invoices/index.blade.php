<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoices | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Fade In Super Smooth */
        .fade-in {
            animation: fadeInUp .8s cubic-bezier(.4,0,.2,1) forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table hover */
        tbody tr:hover {
            background-color: rgb(249 250 251);
        }
    </style>
    
</head>

<body class="bg-gray-100">
<div class="flex">

    @include('layouts.sidebar_admin')

    <div class="flex-1 min-h-screen flex flex-col">
        @include('layouts.header_admin')

        <main class="p-6 w-full space-y-6 fade-in">

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- SEARCH -->
                <form method="GET" class="flex flex-wrap items-end gap-3">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari invoice / customer..."
                       class="px-4 py-2 border rounded-lg">

                <!-- DATE FROM -->
                <input type="date"
                       name="date_from"
                       value="{{ $dateFrom }}"
                       class="px-3 py-2 border rounded-lg">

                <!-- DATE TO -->
                <input type="date"
                       name="date_to"
                       value="{{ $dateTo }}"
                       class="px-3 py-2 border rounded-lg">

                <!-- STATUS -->
                <select name="status"
                        class="px-3 py-2 border rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="PAID" {{ $status == 'PAID' ? 'selected' : '' }}>PAID</option>
                    <option value="UNPAID" {{ $status == 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                </select>

                <!-- BUTTON -->
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg
                               flex items-center gap-2">
                    <i data-lucide="filter"></i>
                    Filter
                </button>

                <!-- RESET -->
                <a href="/invoices"
                   class="px-4 py-2 border rounded-lg">
                    Reset
                    </a>

                </form>

                <!-- EXPORT -->
                <a href="{{ url('/invoices/report/pdf') }}?{{ request()->getQueryString() }}"
                   class="bg-red-600 text-white px-4 py-2 rounded-lg
                          flex items-center gap-2">
                    <i data-lucide="file-text"></i>
                    PDF
                </a>

                <a href="{{ url('/invoices/report/excel') }}?{{ request()->getQueryString() }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg
                          flex items-center gap-2">
                    <i data-lucide="file-spreadsheet"></i>
                    Excel
                </a>

                <!-- ADD BUTTON -->
                <a href="/invoices/create"
                   class="inline-flex items-center gap-2
                          bg-indigo-600 hover:bg-indigo-700
                          text-white px-4 py-2 rounded-xl
                          shadow hover:shadow-lg transition">
                    <i data-lucide="plus"></i>
                    Tambah Invoice
                </a>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-center">No Invoice</th>
                            <th class="p-3 text-center">Customer</th>
                            <th class="p-3 text-center">Tanggal</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Total</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $i)
                        <tr class="border-t text-center hover:bg-gray-50 transition">
                            <td class="p-3 text-center font-medium">
                                {{ $i->invoice_number }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $i->customer_name }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $i->invoice_date }}
                            </td>
                            <td class="p-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold text-white
                                    {{ $i->status === 'PAID' ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ $i->status }}
                                </span>
                            </td>
                            <td class="p-3 text center font-semibold">
                                Rp {{ number_format($i->grand_total,0,',','.') }}
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center gap-3">
                                    <a href="/invoices/show/{{ $i->id }}"
                                       class="text-sky-600 hover:text-sky-800 transition">
                                        <i data-lucide="eye"></i>
                                    </a>

                                    <a href="/invoices/edit/{{ $i->id }}"
                                       class="text-blue-600 hover:text-blue-800 transition">
                                        <i data-lucide="edit"></i>
                                    </a>

                                    <button onclick="hapus({{ $i->id }})"
                                            class="text-red-600 hover:text-red-800 transition">
                                        <i data-lucide="trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                Data invoice tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-end">
                {{ $invoices->appends(['search' => $search ?? null])->links('pagination::tailwind') }}
            </div>

        </main>

        @include('layouts.footer_admin')
    </div>
</div>

<script>
    lucide.createIcons();

    function hapus(id) {
        Swal.fire({
            title: 'Hapus Invoice?',
            text: 'Data invoice tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((r) => {
            if (r.isConfirmed) {
                window.location = '/invoices/delete/' + id;
            }
        });
    }

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session('error') }}'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>

</body>
</html>
