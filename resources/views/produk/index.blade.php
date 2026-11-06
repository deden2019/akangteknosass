@extends('layouts.app')

@section('title', 'Daftar Produk - POS')

@section('content')

    <!-- HEADER / TOMBOL TAMBAH -->
    <div class="flex justify-between items-center mb-1">
        <div>
            <h2 class="text-base font-bold text-slate-800">Daftar Produk</h2>
            <p class="text-xs text-slate-500">Kelola katalog produk, harga jual, dan stok barang.</p>
        </div>
        @if (in_array(session('nama_role'), ['Administrator', 'Owner']))
            <a href="/produk/tambah"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow-sm active:scale-95 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Produk</span>
            </a>
        @endif

    </div>

    <!-- BAR PENCARIAN, TAMPILKAN PER HALAMAN, & INFO TOTAL -->
    <form method="GET"
        class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm">

        <div class="flex flex-1 flex-col sm:flex-row items-center gap-2">
            <!-- Input Search -->
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ request('q') ?? ($keyword ?? '') }}"
                    placeholder="Cari barcode atau nama produk..."
                    class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-1 focus:ring-emerald-500 transition">
                @if (request('q') || !empty($keyword))
                    <a href="{{ request()->url() }}"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>

            <!-- Tombol Cari -->
            <button type="submit"
                class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>
        </div>

        <!-- Filter Per Page (10, 20, 50) & Total Info -->
        <div
            class="flex items-center justify-between sm:justify-end gap-4 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
            <!-- Select Per Page -->
            <div class="flex items-center gap-2">
                <label for="per_page" class="text-xs text-slate-500 font-medium whitespace-nowrap">Tampilkan:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-2.5 py-1.5 focus:outline-none focus:border-emerald-500 font-bold cursor-pointer">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <!-- Total Produk -->
            <div class="text-xs text-slate-500 font-medium whitespace-nowrap border-l border-slate-200 pl-4">
                Total: <span
                    class="font-bold text-slate-800">{{ method_exists($produk, 'total') ? $produk->total() : count($produk) }}</span>
            </div>
        </div>

    </form>

    <!-- 1. TAMPILAN CARD (Khusus Layar Mobile / HP) -->
    <div class="grid grid-cols-1 gap-3 sm:hidden">
        @forelse($produk as $item)
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span
                            class="inline-block px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-mono font-bold tracking-wider uppercase mb-1">
                            {{ $item->kode_produk }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-800 line-clamp-2">{{ $item->nama_produk }}</h3>
                    </div>
                    <!-- Badge Stok di Mobile -->
                    @if ($item->stok_gudang > 10)
                        <span
                            class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full whitespace-nowrap">
                            Stok: {{ $item->stok_gudang }}
                        </span>
                    @elseif($item->stok > 0)
                        <span
                            class="px-2 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-full whitespace-nowrap">
                            Stok: {{ $item->stok_gudang }}
                        </span>
                    @else
                        <span class="px-2 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full whitespace-nowrap">
                            Habis
                        </span>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Harga Jual</span>
                    <span class="text-base font-extrabold text-emerald-600">
                        Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 text-center rounded-2xl border border-slate-100 text-slate-400 text-xs">
                Tidak ada produk yang ditemukan.
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN TABEL (Khusus Tablet & Desktop) -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase text-slate-400 tracking-wider">
                        <th class="py-3.5 px-4">Kode Produk</th>
                        <th class="py-3.5 px-4">Nama Produk</th>
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Satuan</th>
                        <th class="py-3.5 px-4 text-center">Status Stok</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($produk as $item)
                        <tr class="hover:bg-emerald-50/30 transition">
                            <!-- Kode -->
                            <td class="py-3 px-4 font-mono font-medium text-slate-500 text-xs">
                                {{ $item->kode_produk }}
                            </td>
                            <!-- Nama -->
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                {{ $item->nama_produk }}
                            </td>
                            <!-- Harga -->
                            <td class="py-3 px-4 text-right font-bold text-emerald-600">
                                Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                            </td>

                            <td class="py-3 px-4 text-right font-bold text-emerald-600">
                                {{ $item->satuan }}
                            </td>
                            <!-- Stok dengan Indikator Warna -->
                            <td class="py-3 px-4 text-center">
                                @if ($item->stok_gudang > 10)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
                                        {{ $item->stok_gudang }} unit
                                    </span>
                                @elseif($item->stok_gudang > 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                                        {{ $item->stok_gudang }} unit
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3 px-4 text-center">

                                @if (in_array(session('nama_role'), ['Administrator', 'Owner']))
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="/produk/edit/{{ $item->produk_id }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition"
                                            title="Edit Produk">

                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="/produk/hapus/{{ $item->produk_id }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                            @csrf

                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition"
                                                title="Hapus Produk">

                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada produk yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION LINK -->
    @if (method_exists($produk, 'links'))
        <div class="pt-2">
            {{ $produk->appends(request()->query())->links() }}
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endpush
