<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard POS') - Fresh Green Style</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 font-sans min-h-screen pb-20 md:pb-6">

    <!-- TOP BAR (Header Hijau Sesuai Desain) -->
    <header class="bg-emerald-600 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Profile Ringkas (Kiri) -->
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-full bg-emerald-500 border-2 border-emerald-300 flex items-center justify-center font-bold text-white shadow-inner">
                    {{ strtoupper(substr(session('nama_pengguna') ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-sm font-bold leading-tight line-clamp-1">{{ session('nama_pengguna') }}</h2>
                    <p class="text-xs text-emerald-100 flex items-center gap-1 mt-0.5">
                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                        <span>{{ session('cabang_id') ?? 'PUSAT' }}</span>
                    </p>
                </div>
            </div>

            <!-- Role Badge & Logout (Kanan) -->
            <div class="flex items-center space-x-2">
                <span
                    class="bg-emerald-700 text-emerald-100 text-[10px] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider border border-emerald-500">
                    {{ $namaRole ?? 'Staff' }}
                </span>
                <a href="/logout"
                    class="p-2 bg-emerald-700 hover:bg-red-500 text-emerald-100 hover:text-white rounded-lg transition active:scale-95">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- CONTENT CONTAINER (Dinamis dari halaman lain) -->
    <main class="max-w-7xl mx-auto px-4 py-5 space-y-5">
        @yield('content')
    </main>

    <!-- BOTTOM NAVIGATION BAR (Navigasi Bawah Warna Hijau) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-2 z-50 shadow-lg">
        <div class="max-w-md mx-auto flex justify-around items-center">

            <!-- Link Dashboard -->
            <a href="/dashboard"
                class="flex flex-col items-center {{ Request::is('dashboard') ? 'text-emerald-600' : 'text-slate-400 hover:text-emerald-600' }} transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span
                    class="text-[10px] {{ Request::is('dashboard') ? 'font-bold' : 'font-medium' }} mt-1">Beranda</span>
            </a>

            <!-- Link Produk -->
            <a href="/produk"
                class="flex flex-col items-center {{ Request::is('produk*') ? 'text-emerald-600' : 'text-slate-400 hover:text-emerald-600' }} transition">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span class="text-[10px] {{ Request::is('produk*') ? 'font-bold' : 'font-medium' }} mt-1">Produk</span>
            </a>

            <!-- Tombol POS Melayang di Tengah (Hijau) -->
            <a href="/kasir" class="flex flex-col items-center -mt-5">
                <div
                    class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 border-4 border-white active:scale-90 transition-transform">
                    <i data-lucide="calculator" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 mt-0.5">Kasir</span>
            </a>

            <!-- Link Customer -->
            <a href="/customer"
                class="flex flex-col items-center {{ Request::is('customer*') ? 'text-emerald-600' : 'text-slate-400 hover:text-emerald-600' }} transition">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span
                    class="text-[10px] {{ Request::is('customer*') ? 'font-bold' : 'font-medium' }} mt-1">Pelanggan</span>
            </a>

            <!-- Link Laporan -->
            <a href="/laporan/shift-operasional"
                class="flex flex-col items-center {{ Request::is('laporan*') ? 'text-emerald-600' : 'text-slate-400 hover:text-emerald-600' }} transition">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span
                    class="text-[10px] {{ Request::is('laporan*') ? 'font-bold' : 'font-medium' }} mt-1">Laporan</span>
            </a>

            <!-- Link Logout -->
            <a href="/logout" class="flex flex-col items-center text-slate-400 hover:text-red-500 transition">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="text-[10px] font-medium mt-1">Keluar</span>
            </a>

        </div>
    </nav>

    <!-- Inisialisasi Icon -->
    <script>
        lucide.createIcons();
    </script>

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Ditolak',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#059669'
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
