<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard POS') - Fresh Green Style</title>
    <!-- Tailwind CSS CDN -->
    <!-- Favicon standar untuk browser -->
    <link rel="icon" type="image/png" href="{{ asset('images/login22.png') }}">

    <!-- TAMBAHKAN KODE INI KHUSUS UNTUK IOS / LAYAR UTAMA -->
    <link rel="apple-touch-icon" href="{{ asset('images/login22.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/login22.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/login22.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('images/login22.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <!-- Role Badge, Notifikasi & Logout (Kanan) -->
            <div class="flex items-center space-x-2">

                <!-- === TOMBOL & DROPDOWN NOTIFIKASI STOK MENIPIS === -->
                <div class="relative">
                    <button type="button" onclick="toggleDropdownNotif()"
                        class="relative p-2 bg-emerald-700 hover:bg-emerald-800 text-emerald-100 rounded-lg transition focus:outline-none flex items-center justify-center">
                        <i data-lucide="bell" class="w-4 h-4"></i>

                        @if (isset($produkMenipis) && $produkMenipis->count() > 0)
                            <!-- Badge Jumlah Notifikasi -->
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-pulse">
                                {{ $produkMenipis->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Box Notifikasi -->
                    <div id="dropdownNotifMenu"
                        class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 text-slate-800">
                        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Notifikasi Stok
                                Menipis</span>
                            <span
                                class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ isset($produkMenipis) ? $produkMenipis->count() : 0 }}
                                Item</span>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 text-left">
                            @forelse($produkMenipis ?? [] as $item)
                                <!-- Ubah href agar langsung menuju halaman edit produk berdasarkan ID-nya -->
                                <a href="{{ route('produk.edit', $item->produk_id) }}"
                                    class="block px-4 py-3 hover:bg-emerald-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">
                                                {{ $item->nama_produk }}</h4>
                                            <p class="text-[11px] text-slate-500 mt-0.5">Kode: {{ $item->kode_produk }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                                Stok: {{ $item->stok_gudang }}
                                            </span>
                                            <p class="text-[10px] text-slate-400 mt-1">Min: {{ $item->minimal_stok }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-slate-400 text-xs">
                                    <i data-lucide="check-circle" class="w-8 h-8 mx-auto text-emerald-400 mb-1"></i>
                                    Tidak ada produk yang hampir habis.
                                </div>
                            @endforelse
                        </div>

                        @if (isset($produkMenipis) && $produkMenipis->count() > 0)
                            <div class="px-4 py-2 border-t border-slate-100 text-center">
                                <a href="/produk" class="text-[11px] font-bold text-emerald-600 hover:underline">Kelola
                                    Data Produk &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
                <span
                    class="bg-emerald-700 text-emerald-100 text-[10px] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider border border-emerald-500">
                    {{ session('nama_role') ?? 'Staff' }}
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

    <!-- BOTTOM NAVIGATION BAR (Navigasi Bawah dengan Dropdown Laporan) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-2 z-50 shadow-lg">
        <div class="max-w-md mx-auto flex justify-around items-center relative">

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

            <!-- MENU DROPDOWN LAPORAN -->
            <div class="relative">
                <!-- Tombol Trigger Dropdown -->
                <button type="button" onclick="toggleDropdownLaporan()"
                    class="flex flex-col items-center {{ Request::is('laporan*') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-emerald-600' }} transition focus:outline-none">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="text-[10px] mt-1">Laporan</span>
                </button>

                <!-- Box Menu Dropdown (Muncul ke atas) -->
                <div id="dropdownLaporanMenu"
                    class="hidden absolute bottom-14 right-0 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform transition-all">
                    <div
                        class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Pilih Menu Laporan
                    </div>

                    <a href="/laporan/shift-operasional"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                        <i data-lucide="clock" class="w-4 h-4 text-emerald-500"></i>
                        <span>Shift Operasional</span>
                    </a>

                    <a href="/laporan/penjualan-produk"
                        class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-emerald-500"></i>
                        <span>Penjualan Produk</span>
                    </a>
                </div>
            </div>

            <!-- PENGATURAN SISTEM (Hanya Muncul untuk Administrator & Owner) -->
            <!-- MENU DROPDOWN SETTING (Hanya Muncul untuk Administrator & Owner) -->
            @if (in_array(session('nama_role'), ['Administrator', 'Owner']))
                <div class="relative">
                    <!-- Tombol Trigger Dropdown Setting -->
                    <button type="button" onclick="toggleDropdownSetting()"
                        class="flex flex-col items-center {{ Request::is('setting*') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-emerald-600' }} transition focus:outline-none">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                        <span class="text-[10px] mt-1">Setting</span>
                    </button>

                    <!-- Box Menu Dropdown (Muncul ke atas) -->
                    <div id="dropdownSettingMenu"
                        class="hidden absolute bottom-14 right-0 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform transition-all">
                        <div
                            class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            Pilih Pengaturan
                        </div>

                        <a href="{{ route('setting.shift') }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                            <i data-lucide="clock" class="w-4 h-4 text-emerald-500"></i>
                            <span>Shift Operasional</span>
                        </a>

                        <a href="{{ route('setting.kartu') }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                            <i data-lucide="credit-card" class="w-4 h-4 text-emerald-500"></i>
                            <span>Metode Pembayaran</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </nav>

    <!-- Script untuk Toggle Dropdown & Auto Close saat Klik di Luar -->
    <script>
        function toggleDropdownLaporan() {
            const menu = document.getElementById('dropdownLaporanMenu');
            menu.classList.toggle('hidden');
        }

        // Tutup dropdown jika pengguna klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownLaporanMenu');
            const button = dropdown.previousElementSibling;
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <script>
        function toggleDropdownNotif() {
            const menu = document.getElementById('dropdownNotifMenu');
            menu.classList.toggle('hidden');
        }

        // Tutup dropdown jika mengklik di luar area notifikasi
        window.addEventListener('click', function(e) {
            const dropdownNotif = document.getElementById('dropdownNotifMenu');
            if (dropdownNotif) {
                const buttonNotif = dropdownNotif.previousElementSibling;
                if (!dropdownNotif.contains(e.target) && !buttonNotif.contains(e.target)) {
                    dropdownNotif.classList.add('hidden');
                }
            }
        });
    </script>

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

    <script>
        function toggleDropdownLaporan() {
            const menu = document.getElementById('dropdownLaporanMenu');
            const menuSetting = document.getElementById('dropdownSettingMenu');
            if (menuSetting) menuSetting.classList.add('hidden'); // Tutup setting jika buka laporan
            menu.classList.toggle('hidden');
        }

        function toggleDropdownSetting() {
            const menu = document.getElementById('dropdownSettingMenu');
            const menuLaporan = document.getElementById('dropdownLaporanMenu');
            if (menuLaporan) menuLaporan.classList.add('hidden'); // Tutup laporan jika buka setting
            menu.classList.toggle('hidden');
        }

        // Tutup dropdown jika pengguna klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const dropdownLaporan = document.getElementById('dropdownLaporanMenu');
            const dropdownSetting = document.getElementById('dropdownSettingMenu');

            if (dropdownLaporan && !dropdownLaporan.contains(e.target) && !dropdownLaporan.previousElementSibling
                .contains(e.target)) {
                dropdownLaporan.classList.add('hidden');
            }
            if (dropdownSetting && !dropdownSetting.contains(e.target) && !dropdownSetting.previousElementSibling
                .contains(e.target)) {
                dropdownSetting.classList.add('hidden');
            }
        });
    </script>

    @if (isset($produkMenipis) && $produkMenipis->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cek apakah pop-up sudah pernah dimunculkan pada sesi login ini
                if (!sessionStorage.getItem('popup_stok_ditampilkan')) {

                    // Ambil beberapa daftar produk untuk ditampilkan di pesan (maksimal 5 agar tidak kepanjangan)
                    let totalProduk = {{ $produkMenipis->count() }};

                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian: Stok Produk Menipis!',
                        html: `Terdapat <b>${totalProduk} item produk</b> yang stoknya sudah berada di bawah batas minimum.<br>Silakan cek menu produk untuk melakukan restock.`,
                        confirmButtonText: 'Kelola Produk Sekarang',
                        confirmButtonColor: '#059669', // Warna hijau Tailwind emerald-600
                        showCancelButton: true,
                        cancelButtonText: 'Nanti Saja',
                        cancelButtonColor: '#64748b', // Warna abu-abu slate-500
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika user klik tombol konfirmasi, arahkan ke halaman produk
                            window.location.href = '/produk';
                        }
                    });

                    // Tandai bahwa pop-up sudah ditampilkan untuk sesi ini
                    sessionStorage.setItem('popup_stok_ditampilkan', 'true');
                }
            });
        </script>
    @endif
</body>

</html>
