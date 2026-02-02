<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Customers | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert -->
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

        <!-- CONTENT -->
        <main class="p-6 w-full space-y-6 fade-in">

            <!-- TOP BAR -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                <!-- SEARCH -->
                <form method="GET" class="w-full md:w-auto">
                    <div class="relative">
                        <input
                            name="search"
                            value="{{ $search }}"
                            class="pl-10 pr-4 py-2 border rounded-xl
                                   focus:outline-none focus:ring-2
                                   focus:ring-indigo-500 transition"
                            placeholder="Cari customer..."
                        >
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i data-lucide="search"></i>
                        </span>
                    </div>
                </form>

                <!-- ADD BUTTON -->
                <a href="/customers/create"
                   class="bg-indigo-600 hover:bg-indigo-700
                          text-white px-4 py-2 rounded-xl
                          flex items-center gap-2
                          shadow hover:shadow-lg transition">
                    <i data-lucide="plus"></i>
                    Tambah Customer
                </a>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-center">Nama</th>
                            <th class="p-3 text-center">Email</th>
                            <th class="p-3 text-center">Phone</th>
                            <th class="p-3 text-center">Alamat</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                        <tr class="border-t">
                            <td class="p-3 text-center font-medium">
                                {{ $c->customer_name }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $c->customer_email ?? '-' }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $c->customer_phone ?? '-' }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $c->customer_address ?? '-' }}
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center gap-3">
                                    <a href="/customers/edit/{{ $c->id }}"
                                       class="text-blue-600 hover:text-blue-800 transition">
                                        <i data-lucide="edit"></i>
                                    </a>

                                    <button onclick="hapus({{ $c->id }})"
                                       class="text-red-600 hover:text-red-800 transition">
                                        <i data-lucide="trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                Data customer tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-end">
                {{ $customers->appends(['search' => $search])->links('pagination::tailwind') }}
            </div>

        </main>

        @include('layouts.footer_admin')
    </div>
</div>

<script>
    lucide.createIcons();

    function hapus(id){
        Swal.fire({
            title: 'Yakin hapus customer?',
            text: 'Data yang dihapus tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((r) => {
            if(r.isConfirmed){
                window.location = '/customers/delete/' + id;
            }
        });
    }

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
