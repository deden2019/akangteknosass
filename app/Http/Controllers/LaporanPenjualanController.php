<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $cabangId = session('cabang_id');
        $isAdministrator = session('role_id') == 1;

        $jenisFilter = $request->get('jenis_filter', 'tanggal');
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-d'));
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $jenisLaporan = $request->get(
    'jenis_laporan',
    'transaksi'
);
        $tampilkanRincian = $request->has('rincian');
        $selectedCustomers = $request->get('customer_id', []);

        $listOfCustomer = DB::table('m_customer')->get();
        $kolomTanggal = 't.tanggal'; 

        if ($tampilkanRincian) {
            $query = DB::table('t_item_jual_produk as td')
                ->join('t_jual_produk as t', 'td.jual_id', '=', 't.jual_id')
                ->leftJoin('m_customer as c', 't.customer_id', '=', 'c.customer_id')
                ->leftJoin('m_produk as p', 'td.produk_id', '=', 'p.produk_id')
                ->select(
                    DB::raw("$kolomTanggal as created_at"), 
                    't.cabang_id', 
                    'c.nama_customer', 
                    'p.nama_produk', 
                    'td.jumlah', 
                    'td.harga_jual',
                    DB::raw('(td.jumlah * td.harga_jual) as total_harga')

                );
        } else {
            $query = DB::table('t_jual_produk as t')
                ->leftJoin('m_customer as c', 't.customer_id', '=', 'c.customer_id')
                ->leftJoin('m_kartu as k', 't.kartu_id', '=', 'k.kartu_id') // Join ke m_kartu
                ->select(
                    't.*', 
                    'c.nama_customer', 
                    'k.nama_kartu', // Ambil nama jenis pembayaran/kartu
                    DB::raw("$kolomTanggal as created_at"), 
                    't.total_nota as total_harga'
                );
        }

        // Query khusus untuk data Grafik Metode Pembayaran (Pie/Doughnut Chart)
        $queryMetode = DB::table('t_jual_produk as t')
            ->leftJoin('m_kartu as k', 't.kartu_id', '=', 'k.kartu_id')
            ->select(
                DB::raw("COALESCE(k.nama_kartu, 'Cash / Tunai') as metode_pembayaran"),
                DB::raw("SUM(t.total_nota) as total_nominal")
            );

        $queryProduk = DB::table('t_item_jual_produk as td')
    ->join(
        't_jual_produk as t',
        'td.jual_id',
        '=',
        't.jual_id'
    )
    ->join(
        'm_produk as p',
        'td.produk_id',
        '=',
        'p.produk_id'
    )
    ->select(
        'p.kode_produk',
        'p.nama_produk',
        DB::raw('SUM(td.jumlah) as qty_terjual'),
        DB::raw('SUM(td.jumlah * td.harga_jual) as total_penjualan'),
        DB::raw('SUM(td.jumlah * td.harga_beli) as total_modal'),
        DB::raw('SUM(td.jumlah * (td.harga_jual - td.harga_beli)) as laba')
    );

        if ($jenisFilter == 'tanggal') {
            $query->whereBetween(DB::raw($kolomTanggal), [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59']);
            $queryMetode->whereBetween(DB::raw($kolomTanggal), [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59']);
            $queryProduk->whereBetween(
    't.tanggal',
    [
        $tanggalMulai . ' 00:00:00',
        $tanggalSelesai . ' 23:59:59'
    ]
);
            $periode = ($tanggalMulai == $tanggalSelesai) 
                ? "Periode : " . Carbon::parse($tanggalMulai)->translatedFormat('d F Y') 
                : "Periode : " . Carbon::parse($tanggalMulai)->translatedFormat('d F Y') . " s.d " . Carbon::parse($tanggalSelesai)->translatedFormat('d F Y');
        } else {
            $query->whereMonth(DB::raw($kolomTanggal), $bulan)->whereYear(DB::raw($kolomTanggal), $tahun);
            $queryMetode->whereMonth(DB::raw($kolomTanggal), $bulan)->whereYear(DB::raw($kolomTanggal), $tahun);
            $queryProduk
    ->whereMonth('t.tanggal', $bulan)
    ->whereYear('t.tanggal', $tahun);

            $periode = "Periode : " . Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
        }

        if (!$isAdministrator && !empty($cabangId)) {
            $query->where('t.cabang_id', $cabangId);
            $queryMetode->where('t.cabang_id', $cabangId);
            $queryProduk->where(
    't.cabang_id',
    $cabangId
);
        }

        if (!empty($selectedCustomers)) {
            $query->whereIn('t.customer_id', $selectedCustomers);
        }

        $listOfReportPenjualan = $query->orderBy(DB::raw($kolomTanggal), 'desc')->get();
        
        // Ambil hasil rekap metode pembayaran
        $statistikMetode = $queryMetode->groupBy('k.nama_kartu')->get();
        $rekapProduk = $queryProduk
    ->groupBy(
        'p.kode_produk',
        'p.nama_produk'
    )
    ->orderByDesc('qty_terjual')
    ->limit(20)
    ->get();

        $totalKeseluruhan = $listOfReportPenjualan->sum(function ($item) {
            return $item->total_harga ?? $item->sub_total ?? 0;
        });

return view(
    'laporan.penjualan_produk',
compact(
    'listOfCustomer',
    'listOfReportPenjualan',
    'periode',
    'tampilkanRincian',
    'tanggalMulai',
    'tanggalSelesai',
    'jenisFilter',
    'jenisLaporan',
    'totalKeseluruhan',
    'statistikMetode',
    'rekapProduk'
)
);
    }
}