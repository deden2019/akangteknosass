@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Pengaturan Sistem & Master Data</h1>
            <p class="text-xs text-slate-500">Kelola konfigurasi shift kerja, metode pembayaran, dan operasional kasir.</p>
        </div>
        <div class="text-xs font-semibold px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl">
            Area Khusus Admin & Owner
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- Menu Shift -->
        <a href="{{ route('setting.shift') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:border-emerald-500 hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mb-3 group-hover:bg-emerald-600 group-hover:text-white transition">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Shift Operasional</h3>
            <p class="text-xs text-slate-400 mt-1">Atur jam mulai dan jam selesai shift kerja kasir (Pagi, Sore, Malam).</p>
        </a>

        <!-- Menu Metode Pembayaran / Kartu -->
        <a href="{{ route('setting.kartu') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:border-emerald-500 hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold mb-3 group-hover:bg-emerald-600 group-hover:text-white transition">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Metode Pembayaran (Kartu)</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola jenis pembayaran Cash, QRIS, Debit, Visa, & Mastercard.</p>
        </a>

    </div>
</div>
@endsection