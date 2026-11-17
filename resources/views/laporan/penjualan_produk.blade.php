@extends('layouts.app')

@section('title', 'Laporan Penjualan Produk')

@push('styles')
    <!-- DataTables Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

    <style>
        /* Paksa background DataTables wrapper dan tabel menjadi putih */
        #tabelLaporan_wrapper,
        #tabelLaporan {
            background-color: #ffffff !important;
            color: #334155 !important;
        }

        /* Atur background baris tabel agar bersih */
        #tabelLaporan tbody tr {
            background-color: #ffffff !important;
            color: #334155 !important;
        }

        /* Warna saat baris disorot (hover) */
        #tabelLaporan tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Perbaiki warna input search dan select length DataTables */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            padding: 4px 8px !important;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header Card -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-lg font-bold text-slate-800">Laporan Penjualan Produk</h1>
                <p class="text-xs text-slate-500">Filter dan cetak laporan transaksi produk berdasarkan customer dan periode.
                </p>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl">
                {{ $periode }}
            </div>
        </div>

        <!-- Filter Form Section -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('laporan.penjualan') }}" method="GET" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Metode Periode
                        </label>
                        <select name="jenis_filter"
                            class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none focus:border-emerald-500">
                            <option value="tanggal" {{ request('jenis_filter') == 'tanggal' ? 'selected' : '' }}>
                                Berdasarkan Rentang Tanggal
                            </option>

                            <option value="bulan" {{ request('jenis_filter') == 'bulan' ? 'selected' : '' }}>
                                Berdasarkan Bulan & Tahun
                            </option>
                        </select>
                    </div>

                    {{-- TAMBAHAN --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Jenis Laporan
                        </label>

                        <select name="jenis_laporan"
                            class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none">

                            <option value="transaksi"
                                {{ request('jenis_laporan', 'transaksi') == 'transaksi' ? 'selected' : '' }}>
                                Transaksi Penjualan
                            </option>

                            <option value="produk" {{ request('jenis_laporan') == 'produk' ? 'selected' : '' }}>
                                Produk Terlaris
                            </option>

                        </select>
                    </div>

                    @if (request('jenis_filter', 'tanggal') == 'tanggal')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Mulai
                            </label>

                            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                                class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Tanggal Selesai
                            </label>

                            <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                                class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none">
                        </div>


                </div>
            @else
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Bulan</label>
                    <select name="bulan" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('bulan', date('m')) == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}"
                        class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none">
                </div>
                @endif
        </div>

        <!-- Checkbox Tampilkan Rincian -->
        <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
            <input type="checkbox" id="rincian" name="rincian" value="1" {{ $tampilkanRincian ? 'checked' : '' }}
                class="w-4 h-4 accent-emerald-500 rounded">
            <label for="rincian" class="text-xs font-medium text-slate-700 cursor-pointer">Tampilkan rincian
                penjualan produk</label>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end gap-2 pt-2">
            <button type="submit"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Tampilkan Laporan
            </button>
        </div>
        </form>
    </div>

    <!-- KARTU TOTAL KESELURUHAN PENJUALAN -->
    <div
        class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6 rounded-2xl shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs uppercase tracking-wider text-emerald-100 font-semibold">Total Pendapatan (Sesuai
                Filter)</span>
            <h2 class="text-2xl font-extrabold mt-1">Rp {{ number_format($totalKeseluruhan ?? 0, 0, ',', '.') }}</h2>
            <p class="text-xs text-emerald-100 mt-0.5">Dari total {{ count($listOfReportPenjualan) }} transaksi/item
                tercatat</p>
        </div>
        <div class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-medium">
            {{ $periode }}
        </div>
    </div>

    <!-- GRAFIK HANYA MUNCUL JIKA ADA DATA -->
    @if (count($listOfReportPenjualan) > 0)
        <!-- GRID DUA GRAFIK (Tren Harian & Metode Pembayaran) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Grafik Tren Penjualan Harian (Lebar 2 Kolom) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Grafik Tren Penjualan Harian</h3>
                    <span class="text-xs text-slate-400">Visualisasi Berdasarkan Filter Aktif</span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Grafik Metode Pembayaran / Kartu (Lebar 1 Kolom) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Metode Pembayaran</h3>
                    <p class="text-xs text-slate-400 mb-4">Distribusi Cash, QRIS, & Kartu</p>
                </div>
                <div class="relative h-56 w-full flex items-center justify-center">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500 text-center">
                    Persentase berdasarkan nominal transaksi
                </div>
            </div>

        </div>
    @endif

    @if (count($rekapProduk) > 0)

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">

            <div class="flex items-center justify-between mb-4">

                <div>
                    <h3 class="font-bold text-slate-800">
                        Top 20 Produk Terlaris
                    </h3>

                    <p class="text-xs text-slate-500">
                        Berdasarkan jumlah produk terjual pada periode yang dipilih.
                    </p>
                </div>

                <div class="text-xs bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg">
                    {{ count($rekapProduk) }} Produk
                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-xs">

                    <thead>

                        <tr class="bg-slate-50 border-b border-slate-100">

                            <th class="p-3 text-left">
                                Rank
                            </th>

                            <th class="p-3 text-left">
                                Kode Produk
                            </th>

                            <th class="p-3 text-left">
                                Nama Produk
                            </th>

                            <th class="p-3 text-right">
                                Qty Terjual
                            </th>

                            <th class="p-3 text-right">
                                Omzet
                            </th>

                            <th class="p-3 text-right">
                                Modal
                            </th>

                            <th class="p-3 text-right">
                                Laba
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($rekapProduk as $index => $produk)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">

                                <td class="p-3 font-bold text-emerald-600">
                                    #{{ $index + 1 }}
                                </td>

                                <td class="p-3">
                                    {{ $produk->kode_produk }}
                                </td>

                                <td class="p-3 font-medium">
                                    {{ $produk->nama_produk }}
                                </td>

                                <td class="p-3 text-right font-semibold">
                                    {{ number_format($produk->qty_terjual, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-right text-emerald-600 font-semibold">
                                    Rp {{ number_format($produk->total_penjualan, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-right">
                                    Rp {{ number_format($produk->total_modal, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-right font-bold text-blue-600">
                                    Rp {{ number_format($produk->laba, 0, ',', '.') }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

    <!-- Tabel Hasil Laporan dengan DataTables -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="mb-4 font-bold text-xs text-slate-800">
            Hasil Data Laporan ({{ count($listOfReportPenjualan) }} Data ditemukan)
        </div>
        <div class="overflow-x-auto">
            <table id="tabelLaporan" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                        <th class="p-3">No</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Customer</th>
                        @if ($tampilkanRincian)
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3">Qty</th>
                        @endif
                        <th class="p-3 text-right">Total / Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($listOfReportPenjualan as $index => $row)
                        <tr>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $row->created_at }}</td>
                            <td class="p-3 font-semibold">{{ $row->nama_customer ?? '-' }}</td>
                            @if ($tampilkanRincian)
                                <td class="p-3">{{ $row->nama_produk ?? '-' }}</td>
                                <td class="p-3">{{ $row->jumlah ?? 1 }}</td>
                            @endif
                            <td class="p-3 text-right font-bold text-emerald-600">
                                Rp {{ number_format($row->total_harga ?? ($row->sub_total ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tampilkanRincian ? 6 : 4 }}" class="p-6 text-center text-slate-400 italic">
                                Maaf, data laporan penjualan tidak ditemukan untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Sertakan Chart.js & jQuery + DataTables JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Inisialisasi DataTables
            $('#tabelLaporan').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)"
                },
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ]
            });

            // 2. Grafik Tren Penjualan (Chart.js) - Hanya dijalankan jika elemen ada
            const salesCanvas = document.getElementById('salesChart');
            if (salesCanvas) {
                const rawData = @json($listOfReportPenjualan);
                const salesByDate = {};

                rawData.forEach(item => {
                    const dateStr = item.created_at ? item.created_at.split(' ')[0] : 'Unknown';
                    const nominal = parseFloat(item.total_harga || item.sub_total || 0);

                    if (!salesByDate[dateStr]) {
                        salesByDate[dateStr] = 0;
                    }
                    salesByDate[dateStr] += nominal;
                });

                const sortedDates = Object.keys(salesByDate).sort();
                const chartLabels = sortedDates.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                });
                const chartDataValues = sortedDates.map(date => salesByDate[date]);

                new Chart(salesCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartLabels.length > 0 ? chartLabels : ['Tidak ada data'],
                        datasets: [{
                            label: 'Total Penjualan (Rp)',
                            data: chartDataValues.length > 0 ? chartDataValues : [0],
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#059669'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Grafik Metode Pembayaran (Doughnut Chart) - Hanya dijalankan jika elemen ada
            const paymentCanvas = document.getElementById('paymentMethodChart');
            if (paymentCanvas) {
                const statsMetode = @json($statistikMetode ?? []);
                const paymentLabels = statsMetode.map(item => item.metode_pembayaran);
                const paymentValues = statsMetode.map(item => item.total_nominal);

                new Chart(paymentCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: paymentLabels.length > 0 ? paymentLabels : ['Cash / Tunai'],
                        datasets: [{
                            data: paymentValues.length > 0 ? paymentValues : [100],
                            backgroundColor: [
                                '#059669', // Emerald
                                '#10B981', // Green
                                '#3B82F6', // Blue
                                '#F59E0B', // Amber
                                '#EC4899', // Pink
                                '#8B5CF6' // Purple
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        });
    </script>
@endsection
