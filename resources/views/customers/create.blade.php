<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Customer | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .fade-in {
            animation: fadeIn .7s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity:0; transform: translateY(30px); }
            to { opacity:1; transform: translateY(0); }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,.35);
            border-color: rgb(99 102 241);
        }
        .btn-press:active {
            transform: scale(.97);
        }
    </style>
</head>

<body class="bg-gray-100">

<div class="flex">
    {{-- SIDEBAR --}}
    @include('layouts.sidebar_admin')

    <div class="flex-1 min-h-screen flex flex-col">
        {{-- HEADER --}}
        @include('layouts.header_admin')

        {{-- CONTENT --}}
        <main class="p-6 max-w-4xl mx-auto w-full fade-in space-y-6">

            <!-- PAGE TITLE -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                    <i data-lucide="user-plus" class="text-indigo-600"></i>
                    Tambah Customer
                </h1>

                <a href="/customers"
                   class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition">
                    <i data-lucide="arrow-left"></i>
                    Kembali
                </a>
            </div>

            <!-- FORM CARD -->
            <div class="bg-white rounded-2xl shadow-lg p-6">

                <form method="POST" action="/customers/store" class="space-y-5">
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label class="text-sm text-gray-600">Nama Customer</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i data-lucide="user"></i>
                            </span>
                            <input
                                type="text"
                                name="customer_name"
                                required
                                class="w-full pl-10 pr-3 py-2 border rounded-xl
                                       focus:outline-none input-focus transition"
                                placeholder="Nama lengkap customer"
                            >
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i data-lucide="mail"></i>
                            </span>
                            <input
                                type="email"
                                name="customer_email"
                                class="w-full pl-10 pr-3 py-2 border rounded-xl
                                       focus:outline-none input-focus transition"
                                placeholder="email@contoh.com"
                            >
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="text-sm text-gray-600">No. Telepon</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i data-lucide="phone"></i>
                            </span>
                            <input
                                type="text"
                                name="customer_phone"
                                class="w-full pl-10 pr-3 py-2 border rounded-xl
                                       focus:outline-none input-focus transition"
                                placeholder="08xxxxxxxxxx"
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="text-sm text-gray-600">Alamat</label>
                        <div class="relative mt-1">
                            <span class="absolute top-3 left-3 text-gray-400">
                                <i data-lucide="map-pin"></i>
                            </span>
                            <textarea
                                name="customer_address"
                                rows="4"
                                class="w-full pl-10 pr-3 py-2 border rounded-xl
                                       focus:outline-none input-focus transition"
                                placeholder="Alamat lengkap customer"
                            ></textarea>
                        </div>
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="/customers"
                           class="px-5 py-2 rounded-xl border
                                  text-gray-600 hover:bg-gray-100 transition">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="flex items-center gap-2 px-6 py-2.5
                                   bg-indigo-600 hover:bg-indigo-700
                                   text-white rounded-xl font-medium
                                   shadow-md hover:shadow-lg
                                   transition btn-press"
                        >
                            <i data-lucide="save"></i>
                            Simpan
                        </button>
                    </div>
                </form>

            </div>

        </main>

        {{-- FOOTER --}}
        @include('layouts.footer_admin')
    </div>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>
