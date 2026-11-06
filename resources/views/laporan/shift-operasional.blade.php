@extends('layouts.app')

@section('title', 'Laporan Shift Operasional')

@section('content')

    <!-- HEADER INFORMASI HALAMAN -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-base font-bold text-slate-800">Laporan Shift Operasional</h2>
            <p class="text-xs text-slate-500">Pilih tanggal, kasir, dan shift untuk melihat rekapitulasi penjualan.</p>
        </div>
    </div>

    <!-- FORM FILTER & TOMBOL AKSI -->
    <form method="POST" action="/laporan/shift-operasional"
        class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Tanggal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Tanggal
                </label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $tanggal ?? date('Y-m-d')) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-1 focus:ring-emerald-500 transition">
            </div>

            <!-- Kasir -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kasir
                </label>
                <select name="kasir_id"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-1 focus:ring-emerald-500 transition cursor-pointer">
                    @foreach ($kasir as $item)
                        <option value="{{ $item->pengguna_id }}"
                            {{ isset($kasir_id) && $kasir_id == $item->pengguna_id ? 'selected' : '' }}>
                            {{ $item->nama_pengguna }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Shift -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Shift
                </label>
                <select name="shift_id"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-1 focus:ring-emerald-500 transition cursor-pointer">
                    @foreach ($shift as $item)
                        <option value="{{ $item->shift_id }}"
                            {{ isset($shift_id) && $shift_id == $item->shift_id ? 'selected' : '' }}>
                            {{ $item->nama_shift }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
            <button type="submit"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl flex items-center gap-1.5 shadow-sm shadow-emerald-100 active:scale-95 transition">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Tampilkan Laporan</span>
            </button>

            @if (isset($laporan))
                <button type="button" onclick="print58()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl flex items-center gap-1.5 shadow-sm active:scale-95 transition">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Print 58mm</span>
                </button>

                <button type="button" onclick="print80()"
                    class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs px-4 py-2.5 rounded-xl flex items-center gap-1.5 shadow-sm active:scale-95 transition">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Print 80mm</span>
                </button>
            @endif
        </div>

        <!-- THERMAL PRINT TEMPLATE (Hidden for Stacking/Printing) -->
        <div id="thermal-print" class="hidden">
            <div class="text-center">
                <strong>KOPERASI RSPB</strong><br>
                LAPORAN SHIFT OPERASIONAL<br>
                {{ $laporan['nama_kasir'] ?? '' }}
            </div>
            <hr>
            Periode<br>
            {{ isset($mulai) ? $mulai->format('d/m/Y H:i') : '' }}
            -
            {{ isset($selesai) ? $selesai->format('d/m/Y H:i') : '' }}
            <hr>
            Tunai {{ number_format($laporan['tunai'] ?? 0, 0, ',', '.') }}<br>
            Piutang {{ number_format($laporan['piutang'] ?? 0, 0, ',', '.') }}<br><br>

            @foreach ($laporan['detail_kartu'] ?? [] as $item)
                {{ $item->nama_kartu }} {{ number_format($item->total, 0, ',', '.') }}<br>
            @endforeach

            <hr>
            Saldo Awal {{ number_format($laporan['saldo_awal'] ?? 0, 0, ',', '.') }}<br>
            TOTAL JUAL {{ number_format($laporan['total_jual'] ?? 0, 0, ',', '.') }}
        </div>

    </form>

    <!-- RINGKASAN SHIFT (HASIL LAPORAN) -->
    @if (isset($laporan))
        <div class="mt-4 bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-2">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Ringkasan Shift</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Kasir: <span class="font-semibold text-slate-700">{{ $laporan['nama_kasir'] }}</span>
                    </p>
                </div>
                @if (isset($mulai) && isset($selesai))
                    <div
                        class="text-xs font-medium bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-100 self-start sm:self-auto">
                        Periode: {{ $mulai->format('d/m/Y H:i') }} - {{ $selesai->format('d/m/Y H:i') }}
                    </div>
                @endif
            </div>

            <div class="space-y-2.5 text-xs sm:text-sm">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600">Saldo Awal</span>
                    <span class="font-semibold text-slate-800">Rp
                        {{ number_format($laporan['saldo_awal'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600">Tunai</span>
                    <span class="font-semibold text-slate-800">Rp
                        {{ number_format($laporan['tunai'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600">Piutang</span>
                    <span class="font-semibold text-slate-800">Rp
                        {{ number_format($laporan['piutang'], 0, ',', '.') }}</span>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 border border-slate-100">
                    <div class="font-bold text-slate-700 text-xs uppercase tracking-wider">Non Tunai</div>
                    @forelse($laporan['detail_kartu'] ?? [] as $kartu)
                        <div class="flex justify-between text-slate-600">
                            <span>{{ $kartu->nama_kartu }}</span>
                            <span class="font-semibold">Rp {{ number_format($kartu->total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="text-slate-400 italic text-xs">Tidak ada transaksi non-tunai.</div>
                    @endforelse
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600">Diskon</span>
                    <span class="font-semibold text-red-600">- Rp
                        {{ number_format($laporan['diskon'], 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600">PPN</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($laporan['ppn'], 0, ',', '.') }}</span>
                </div>

                <div
                    class="flex justify-between items-center bg-emerald-50 border border-emerald-100 rounded-xl p-3.5 mt-2">
                    <span class="font-bold text-emerald-800 text-sm">Total Jual</span>
                    <span class="font-extrabold text-emerald-600 text-base sm:text-lg">
                        Rp {{ number_format($laporan['total_jual'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script src="{{ secure_asset('js/print-shift-operasional.js') }}"></script>
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endpush
