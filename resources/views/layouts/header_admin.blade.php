<header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">

    <!-- LEFT: Logo + Title -->
    <div class="flex items-center gap-3">
        <img src="{{ asset('invoice3.png') }}"
             class="w-10 h-10 rounded-xl shadow"
             alt="Logo">

        <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="file-text" class="text-indigo-600"></i>
            E-Invoice System
        </h1>
    </div>

    <!-- CENTER: Welcome -->
    <div class="hidden md:flex items-center gap-2 text-gray-700">
        <span class="animate-bounce">😄👋</span>
        <span>
            Selamat Datang Kembali,
            <strong>{{ session('full_name') }}</strong>
            <span class="text-sm text-gray-500">
                ({{ session('role_id') == 1 ? 'Admin' : 'Finance' }})
            </span>
        </span>
    </div>

    <!-- RIGHT: Date & Time -->
    <div class="text-right text-sm text-gray-600">
        <div id="current-date"></div>
        <div id="current-time" class="font-medium text-indigo-600"></div>
    </div>
</header>

<!-- ================= DATE & TIME SCRIPT ================= -->
<script>
function updateDateTime() {
    const now = new Date().toLocaleString("id-ID", {
        timeZone: "Asia/Jakarta",
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    });

    const parts = now.split(" ");
    document.getElementById("current-date").innerText =
        parts.slice(0, 4).join(" ");
    document.getElementById("current-time").innerText =
        parts.slice(4).join(" ");
}

setInterval(updateDateTime, 1000);
updateDateTime();
</script>

<!-- ================= SWEETALERT LOGIN SUCCESS ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('login_success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil Login',
    text: 'Selamat datang kembali, {{ session('login_name') }} 🔥😁',
    timer: 3000,
    showConfirmButton: false,
    backdrop: `
        rgba(99,102,241,0.25)
    `
});
</script>
@endif
