<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Settings Company | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fade-in { animation: fadeInUp .8s cubic-bezier(.4,0,.2,1) forwards }
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(30px) }
            to { opacity:1; transform: translateY(0) }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,.35);
            border-color: rgb(99 102 241);
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex">

    @include('layouts.sidebar_admin')

    <div class="flex-1 min-h-screen flex flex-col">
        @include('layouts.header_admin')

        <main class="p-6 max-w-4xl mx-auto w-full fade-in space-y-6">

            <!-- TITLE -->
            <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="settings" class="text-indigo-600"></i>
                Settings Company
            </h1>

            <!-- FORM -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form method="POST"
                      action="{{ $company ? url('/settings/update/'.$company->id) : url('/settings/store') }}"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf

                    <!-- LOGO -->
                    <div>
                        <label class="text-sm text-gray-600">Logo Company</label>

                        @if($company && $company->company_logo)
                            <div class="mt-2 mb-3">
                                <img src="{{ asset($company->company_logo) }}"
                                     class="h-20 rounded-lg shadow border">
                            </div>
                        @endif

                        <input type="file"
                               name="company_logo"
                               accept="image/*"
                               class="w-full px-3 py-2 border rounded-xl
                                      focus:outline-none input-focus transition">
                    </div>

                    <!-- COMPANY NAME -->
                    <div>
                        <label class="text-sm text-gray-600">Nama Company</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="building"></i>
                            </span>
                            <input type="text"
                                   name="company_name"
                                   value="{{ old('company_name', $company->company_name ?? '') }}"
                                   required
                                   class="w-full pl-10 pr-3 py-2 border rounded-xl
                                          focus:outline-none input-focus transition"
                                   placeholder="Nama perusahaan">
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div>
                        <label class="text-sm text-gray-600">Alamat</label>
                        <div class="relative mt-1">
                            <span class="absolute top-3 left-3 text-gray-400">
                                <i data-lucide="map-pin"></i>
                            </span>
                            <textarea name="company_address"
                                      rows="4"
                                      required
                                      class="w-full pl-10 pr-3 py-2 border rounded-xl
                                             focus:outline-none input-focus transition">{{ old('company_address', $company->company_address ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- PHONE -->
                    <div>
                        <label class="text-sm text-gray-600">Phone</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="phone"></i>
                            </span>
                            <input type="text"
                                   name="company_phone"
                                   value="{{ old('company_phone', $company->company_phone ?? '') }}"
                                   class="w-full pl-10 pr-3 py-2 border rounded-xl
                                          focus:outline-none input-focus transition"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="mail"></i>
                            </span>
                            <input type="email"
                                   name="company_email"
                                   value="{{ old('company_email', $company->company_email ?? '') }}"
                                   class="w-full pl-10 pr-3 py-2 border rounded-xl
                                          focus:outline-none input-focus transition"
                                   placeholder="email@company.com">
                        </div>
                    </div>

                    <!-- ACTION -->
                    <div class="flex justify-between pt-4">
                        @if($company)
                        <button type="button"
                                onclick="hapus({{ $company->id }})"
                                class="flex items-center gap-2 px-5 py-2
                                       bg-red-600 hover:bg-red-700
                                       text-white rounded-xl transition">
                            <i data-lucide="trash"></i>
                            Hapus
                        </button>
                        @endif

                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-2.5
                                       bg-indigo-600 hover:bg-indigo-700
                                       text-white rounded-xl font-medium
                                       shadow-md hover:shadow-lg transition">
                            <i data-lucide="save"></i>
                            {{ $company ? 'Update' : 'Simpan' }}
                        </button>
                    </div>

                </form>
            </div>

        </main>

        @include('layouts.footer_admin')
    </div>
</div>

<script>
    lucide.createIcons();

    function hapus(id){
        Swal.fire({
            title: 'Yakin hapus data company?',
            text: 'Data ini digunakan pada Invoice',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((r)=>{
            if(r.isConfirmed){
                window.location = '/settings/delete/' + id;
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
