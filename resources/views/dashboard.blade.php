@extends('layouts.app')

@section('title', 'Dashboard POS')

@section('content')
    <!-- WELCOME BANNER (Green Gradient) -->
    <div
        class="bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-100 relative overflow-hidden">
        <div class="relative z-10">
            <span class="inline-block px-2.5 py-0.5 bg-white/20 backdrop-blur-md rounded-md text-[11px] font-medium mb-2">
                Aplikasi Kasir Online
            </span>
            <h1 class="text-xl font-extrabold">Halo, {{ session('nama_pengguna') }}! 👋</h1>
            <p class="text-xs text-emerald-100 mt-1 max-w-sm">Siap melayani transaksi hari ini di cabang <span
                    class="font-bold underline decoration-emerald-300">{{ session('cabang_id') ?? 'PUSAT' }}</span>?</p>
        </div>
        <!-- Hiasan Background Pattern -->
        <div class="absolute -right-4 -bottom-6 opacity-20 pointer-events-none">
            <i data-lucide="shopping-bag" class="w-36 h-36"></i>
        </div>
    </div>

    <!-- MENU UTAMA / QUICK ACTIONS -->
    <div>
        <h3 class="text-sm font-bold text-slate-700 mb-3 px-1">Menu Utama</h3>
        <div class="grid grid-cols-4 gap-3">

            <!-- Menu Kasir (Highlight Utama Hijau) -->
            <a href="/kasir"
                class="col-span-2 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl p-4 flex flex-col justify-between shadow-md active:scale-95 transition-transform h-28 border border-emerald-400">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                    <i data-lucide="calculator" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <p class="text-base font-bold">Mesin Kasir</p>
                    <p class="text-[11px] text-emerald-100">Buka Transaksi POS</p>
                </div>
            </a>

            <!-- Menu Produk -->
            <a href="/produk"
                class="col-span-1 bg-white border border-slate-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-sm active:scale-95 transition-transform h-28">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-semibold text-slate-700">Produk</span>
            </a>

            <!-- Menu Customer -->
            <a href="/customer"
                class="col-span-1 bg-white border border-slate-100 rounded-2xl p-3 flex flex-col items-center justify-center text-center shadow-sm active:scale-95 transition-transform h-28">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-xs font-semibold text-slate-700">Pelanggan</span>
            </a>

        </div>
    </div>

    <!-- INFO AKUN & CABANG -->
    <div>
        <h3 class="text-sm font-bold text-slate-700 mb-3 px-1">Informasi Sesi</h3>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 divide-y divide-slate-100">

            <!-- Row User -->
            <div class="flex items-center justify-between py-2.5 first:pt-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-slate-100 text-slate-600 rounded-lg">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Pengguna</span>
                </div>
                <span class="text-xs font-bold text-slate-800">{{ session('nama_pengguna') }}</span>
            </div>

            <!-- Row Cabang -->
            <div class="flex items-center justify-between py-2.5">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Outlet / Cabang</span>
                </div>
                <span class="text-xs font-bold text-slate-800">{{ session('cabang_id') ?? 'PUSAT' }}</span>
            </div>

            <!-- Row Role -->
            <div class="flex items-center justify-between py-2.5 last:pb-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Hak Akses</span>
                </div>
                <span class="text-xs font-bold text-slate-800 uppercase">
                    {{ $namaRole ?? 'Staff' }}
                </span>
            </div>

        </div>
    </div>
@endsection
