<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | E-Invoice</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('invoice3.png') }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <<style>
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
    style="background-image: url('{{ asset('invoice2.jpg') }}');">

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
            alt="Icon">

        <h1 class="text-2xl font-semibold text-gray-800 flex justify-center items-center gap-2">
            <i data-lucide="file-text"></i>
            Login E-Invoice
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Silakan login untuk melanjutkan
        </p>
    </div>

    <!-- Form -->
    <div class="p-6">

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-600 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-600 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-5" id="loginForm">
            @csrf

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
                        placeholder="Masukkan username"
                        required>
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
                        placeholder="Masukkan password"
                        required
                    >
                </div>
            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full flex justify-center items-center gap-2
                bg-indigo-600 hover:bg-indigo-700
                text-white py-2.5 rounded-xl font-medium
                transition-all duration-200
                shadow-md hover:shadow-xl btn-press">
                <i data-lucide="log-in"></i>
                Login
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="/register" class="text-indigo-600 hover:underline font-medium">
                Daftar di sini
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
document.getElementById('loginForm').addEventListener('submit', function () {

    Swal.fire({
        title: 'Sedang Memproses Login',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

});
</script>

@if(session('logout_success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil LogOut',
    text: 'Sampai jumpa kembali, {{ session('logout_name') }} 😁👋',
    timer: 3000,
    showConfirmButton: false,
    backdrop: `
        rgba(99,102,241,0.25)
    `
});
</script>
@endif

@if(session('register_success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil Register',
    text: 'Silahkan Login 😁👍🏻',
    timer: 3000,
    showConfirmButton: false,
    backdrop: `
        rgba(99,102,241,0.25)
    `
});
</script>
@endif

</body>
</html>
