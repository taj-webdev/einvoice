<aside class="w-64 min-h-screen bg-gradient-to-b from-indigo-600 to-indigo-700
              text-white flex flex-col">

    <!-- Top Image -->
    <div class="p-6 text-center border-b border-indigo-500">
        <img src="{{ asset('invoice3.png') }}"
             class="w-full h-32 object-cover rounded-xl shadow mb-3"
             alt="Sidebar Image">

        <h2 class="text-lg font-semibold tracking-wide">
            E-Invoice System
        </h2>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2">

        <a href="/dashboard"
           class="flex items-center gap-3 px-4 py-2 rounded-xl
                  hover:bg-white/20 transition">
            <i data-lucide="layout-dashboard" class="text-yellow-300"></i>
            Dashboard
        </a>

        <a href="/customers"
           class="flex items-center gap-3 px-4 py-2 rounded-xl
                  hover:bg-white/20 transition">
            <i data-lucide="users" class="text-green-300"></i>
            Customers
        </a>

        <a href="/invoices"
           class="flex items-center gap-3 px-4 py-2 rounded-xl
                  hover:bg-white/20 transition">
            <i data-lucide="file-text" class="text-blue-300"></i>
            Invoices
        </a>

        <a href="/settings"
           class="flex items-center gap-3 px-4 py-2 rounded-xl
                  hover:bg-white/20 transition">
            <i data-lucide="settings" class="text-pink-300"></i>
            Settings
        </a>

        <hr class="border-indigo-400 my-4">

        <!-- LOGOUT BUTTON -->
        <button onclick="confirmLogout()"
           class="w-full flex items-center gap-3 px-4 py-2 rounded-xl
                  hover:bg-red-500/80 transition text-red-200 hover:text-white">
            <i data-lucide="log-out"></i>
            Log Out
        </button>

    </nav>
</aside>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Konfirmasi LogOut',
        text: 'Apakah Anda yakin ingin LogOut?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            // Loading
            Swal.fire({
                title: 'Sedang memproses...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Delay biar animasi terasa smooth
            setTimeout(() => {
                window.location.href = '/logout';
            }, 1200);
        }
    });
}
</script>
