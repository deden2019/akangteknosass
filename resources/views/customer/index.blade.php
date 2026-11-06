@extends('layouts.app')

@section('title', 'Daftar Customer')

@section('content')

    <!-- BAR PENCARIAN & FILTER PER PAGE -->
    <form method="GET"
        class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm">

        <div class="flex flex-1 flex-col sm:flex-row items-center gap-2">
            <!-- Input Search -->
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="q" value="{{ $keyword ?? '' }}" placeholder="Cari nama atau telepon..."
                    class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500 focus:bg-white focus:ring-1 focus:ring-emerald-500 transition">
                @if (!empty($keyword))
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
            <div class="flex items-center gap-2">
                <label for="per_page" class="text-xs text-slate-500 font-medium whitespace-nowrap">Tampilkan:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-2.5 py-1.5 focus:outline-none focus:border-emerald-500 font-bold cursor-pointer">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <div class="text-xs text-slate-500 font-medium whitespace-nowrap border-l border-slate-200 pl-4">
                Total: <span
                    class="font-bold text-slate-800">{{ method_exists($customer, 'total') ? $customer->total() : count($customer) }}</span>
            </div>
        </div>

    </form>

    <!-- TOMBOL TAMBAH CUSTOMER (Responsive Header Action) -->
    <div class="flex justify-between items-center">
        <h2 class="text-sm font-bold text-slate-700 px-1">Data Pelanggan</h2>
        <a href="/customer/tambah"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow-sm active:scale-95 transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Customer</span>
        </a>
    </div>

    <!-- 1. TAMPILAN CARD (Khusus Layar Mobile / HP) -->
    <div class="grid grid-cols-1 gap-3 sm:hidden">
        @forelse($customer as $item)
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <span
                            class="inline-block px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[10px] font-mono font-bold uppercase mb-1">
                            ID: {{ $item->customer_id }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-800">{{ $item->nama_customer }}</h3>
                    </div>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-xs">
                    <span class="text-slate-400 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i> Telepon
                    </span>
                    <span class="font-semibold text-slate-700">
                        {{ $item->telepon ?? '-' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 text-center rounded-2xl border border-slate-100 text-slate-400 text-xs">
                Tidak ada data customer yang ditemukan.
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
                        <th class="py-3.5 px-4">ID Customer</th>
                        <th class="py-3.5 px-4">Nama Customer</th>
                        <th class="py-3.5 px-4">Telepon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($customer as $item)
                        <tr class="hover:bg-emerald-50/30 transition">
                            <td class="py-3 px-4 font-mono font-medium text-slate-500 text-xs">
                                {{ $item->customer_id }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                {{ $item->nama_customer }}
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium">
                                {{ $item->telepon ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada data customer yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION LINK -->
    @if (method_exists($customer, 'links'))
        <div class="pt-2">
            {{ $customer->appends(request()->query())->links() }}
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        // Inisialisasi ulang icon jika diperlukan setelah render dinamis
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endpush
