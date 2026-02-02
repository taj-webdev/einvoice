<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Smooth Fade + Scale */
        @keyframes cardEnter {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Glow pulse halus */
        @keyframes softGlow {
            0%, 100% {
                box-shadow: 0 20px 50px rgba(0,0,0,.25);
            }
            50% {
                box-shadow: 0 30px 70px rgba(99,102,241,.35);
            }
        }

        .card-animate {
            animation:
                cardEnter 0.9s cubic-bezier(.4,0,.2,1) forwards,
                softGlow 6s ease-in-out infinite;
        }

        /* Input focus glow */
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,.35);
            border-color: rgb(99 102 241);
        }

        /* Button press effect */
        .btn-press:active {
            transform: scale(.97);
        }
    </style>
</head>

<body
    class="min-h-screen flex items-center justify-center bg-cover bg-center"
    style="background-image: url('{{ asset('invoice2.jpg') }}');"
>

<!-- Overlay -->
<div class="absolute inset-0 bg-black/40"></div>

<!-- Card -->
<div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-xl
            rounded-3xl border border-white/40
            overflow-hidden card-animate">

    <!-- Header -->
    <div class="bg-gradient-to-br from-indigo-50 to-white p-7 text-center border-b">
        <img
            src="{{ asset('invoice3.png') }}"
            class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-lg
                   hover:rotate-6 transition-transform duration-300"
            alt="Icon"
        >

        <h1 class="text-2xl font-semibold text-gray-800 flex justify-center items-center gap-2">
            <i data-lucide="user-plus"></i>
            Register E-Invoice
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Buat akun untuk mengelola invoice
        </p>
    </div>

    <!-- Form -->
    <div class="p-6">

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-600 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-5" id="registerForm">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="text-sm text-gray-600">Nama Lengkap</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="id-card"></i>
                    </span>
                    <input
                        type="text"
                        name="full_name"
                        class="w-full pl-10 pr-3 py-2 border rounded-xl
                               focus:outline-none transition input-glow"
                        placeholder="Nama lengkap"
                        required
                    >
                </div>
            </div>

            <!-- Username -->
            <div>
                <label class="text-sm text-gray-600">Username</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="user"></i>
                    </span>
                    <input
                        type="text"
                        name="username"
                        class="w-full pl-10 pr-3 py-2 border rounded-xl
                               focus:outline-none transition input-glow"
                        placeholder="Username"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        class="w-full pl-10 pr-3 py-2 border rounded-xl
                               focus:outline-none transition input-glow"
                        placeholder="Password"
                        required
                    >
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="text-sm text-gray-600">Role User</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="shield"></i>
                    </span>
                    <select
                        name="role_id"
                        class="w-full pl-10 pr-3 py-2 border rounded-xl
                               focus:outline-none transition input-glow"
                        required
                    >
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full flex justify-center items-center gap-2
                       bg-indigo-600 hover:bg-indigo-700
                       text-white py-2.5 rounded-xl font-medium
                       transition-all duration-200
                       shadow-md hover:shadow-xl btn-press"
            >
                <i data-lucide="user-plus"></i>
                Register
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="/login" class="text-indigo-600 hover:underline font-medium">
                Login di sini
            </a>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('registerForm').addEventListener('submit', function () {

    Swal.fire({
        title: 'Sedang Memproses Registrasi',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

});
</script>

</body>
</html>
