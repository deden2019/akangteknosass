<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;

class LaporanShiftOperasionalController extends Controller
{
    public function index()
    {
        $isAdmin =
            session('role_id') ==
            '11dc1faf-2c66-4525-932d-a90e24da8987';

        if ($isAdmin)
        {
            $kasir = DB::table('m_pengguna')
                ->orderBy('nama_pengguna')
                ->get();
        }
        else
        {
            $kasir = DB::table('m_pengguna')
                ->where(
                    'pengguna_id',
                    session('pengguna_id')
                )
                ->get();
        }

        $shift = DB::table('m_shift')
            ->orderBy('nama_shift')
            ->get();

        return view(
            'laporan.shift-operasional',
            compact(
                'kasir',
                'shift',
                'isAdmin'
            )
        );
    }
public function generate(Request $request)
{
    $tanggal = $request->tanggal;

    $isAdmin =
        session('role_id') ==
        '11dc1faf-2c66-4525-932d-a90e24da8987';

    if ($isAdmin)
    {
        $kasirId = $request->kasir_id;
    }
    else
    {
        $kasirId = session('pengguna_id');
    }

    $shiftId = $request->shift_id;

    $shiftData = DB::table('m_shift')
    ->where('shift_id', $shiftId)
    ->first();

if (!$shiftData) {
    return back()->with(
        'error',
        'Data shift tidak ditemukan'
    );
}

switch (strtoupper($shiftData->nama_shift))
{
    case 'SHIFT PAGI':

        $mulai = Carbon::parse($tanggal)
            ->setTime(7, 0);

        $selesai = Carbon::parse($tanggal)
            ->setTime(15, 0);

        break;

    case 'SHIFT SORE':

        $mulai = Carbon::parse($tanggal)
            ->setTime(15, 0);

        $selesai = Carbon::parse($tanggal)
            ->setTime(22, 0);

        break;

    case 'SHIFT MALAM':

        $mulai = Carbon::parse($tanggal)
            ->setTime(22, 0);

        $selesai = Carbon::parse($tanggal)
            ->addDay()
            ->setTime(7, 0);

        break;

    default:

        return back()->with(
            'error',
            'Shift tidak ditemukan'
        );
}
$namaKasir = DB::table('m_pengguna')
    ->where('pengguna_id', $kasirId)
    ->value('nama_pengguna');

$tunai = DB::table('t_jual_produk')
    ->where('pengguna_id', $kasirId)
    ->where('tanggal_sistem', '>=', $mulai)
    ->where('tanggal_sistem', '<', $selesai)
    ->whereNull('tanggal_tempo')
    ->whereNull('kartu_id')
    ->sum(
        DB::raw(
            'total_nota - diskon + ppn + ongkos_kirim'
        )
    );

$piutang = DB::table('t_jual_produk')
    ->where('pengguna_id', $kasirId)
    ->where('tanggal_sistem', '>=', $mulai)
    ->where('tanggal_sistem', '<', $selesai)
    ->whereNotNull('tanggal_tempo')
    ->sum(
        DB::raw(
            'total_nota - diskon + ppn + ongkos_kirim'
        )
    );

$kartu = DB::table('t_jual_produk')
    ->where('pengguna_id', $kasirId)
    ->where('tanggal_sistem', '>=', $mulai)
    ->where('tanggal_sistem', '<', $selesai)
    ->whereNotNull('kartu_id')
    ->sum(
        DB::raw(
            'total_nota - diskon + ppn + ongkos_kirim'
        )
    );

$pembayaranKartu = DB::table('t_jual_produk')
    ->join(
        'm_kartu',
        'm_kartu.kartu_id',
        '=',
        't_jual_produk.kartu_id'
    )
    ->select(
        'm_kartu.nama_kartu',
        DB::raw(
            'SUM(total_nota - diskon + ppn + ongkos_kirim) as total'
        )
    )
    ->where('pengguna_id', $kasirId)
    ->where('tanggal_sistem', '>=', $mulai)
    ->where('tanggal_sistem', '<', $selesai)
    ->groupBy('m_kartu.nama_kartu')
    ->orderBy('m_kartu.nama_kartu')
    ->get();

    $saldoAwal =
        DB::table('t_mesin')
        ->where('pengguna_id', $kasirId)
        ->where('shift_id', $shiftId)
        ->whereDate('tanggal', $tanggal)
        ->value('saldo_awal');

$laporan = [

    'nama_kasir' => $namaKasir,

    'saldo_awal' => $saldoAwal ?? 0,

    'tunai' => $tunai,

    'piutang' => $piutang,

    'kartu' => $kartu,

    'detail_kartu' => $pembayaranKartu,

    'diskon' => 0,

    'ppn' => 0,

    'total_jual' =>
        $tunai +
        $piutang +
        $kartu,
];

if ($isAdmin)
{
    $kasir = DB::table('m_pengguna')
        ->orderBy('nama_pengguna')
        ->get();
}
else
{
    $kasir = DB::table('m_pengguna')
        ->where(
            'pengguna_id',
            session('pengguna_id')
        )
        ->get();
}


$shift = DB::table('m_shift')
    ->orderBy('nama_shift')
    ->get();

return view(
    'laporan.shift-operasional',
    compact(
        'kasir',
        'shift',
        'laporan',
        'isAdmin',
        'mulai',
        'selesai'
    )
);
}
}