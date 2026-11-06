<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasirController extends Controller
{
   public function index()
{
    $roleId = session('role_id');

    // Administrator, Owner, Supervisor
    if (
        in_array(
            $roleId,
            [
                '11dc1faf-2c66-4525-932d-a90e24da8987',
                'c58ee40a-5ae2-4067-b6ad-8cae9c65913c',
                '29656af0-c3f6-44e8-9c74-c3643f38e871'
            ]
        )
    )
    {
        return view('kasir.index');
    }

    $shiftId = session('shift_id');

    $jam = now()->format('H:i:s');

    $bolehMasuk = false;

    // SHIFT PAGI
    if (
        $shiftId ==
        'cd0c46ea-64f2-4737-8fcd-40b5963a5543'
    )
    {
        $bolehMasuk =
            $jam >= '07:00:00'
            && $jam < '15:00:00';
    }

    // SHIFT SORE
    if (
        $shiftId ==
        '9352cbc2-ca26-460b-af82-26c4063e2b9e'
    )
    {
        $bolehMasuk =
            $jam >= '15:00:00'
            && $jam < '22:00:00';
    }

    // SHIFT MALAM
    if (
        $shiftId ==
        '9b8862c0-5807-4923-b87c-e217ca3a1a98'
    )
    {
        $bolehMasuk =
            $jam >= '22:00:00'
            || $jam < '07:00:00';
    }

    if (!$bolehMasuk)
    {
        return redirect('/dashboard')
            ->with(
                'error',
                'Belum memasuki jam operasional shift'
            );
    }

    return view('kasir.index');
}
    public function struk($jualId)
{
    $header = DB::table('m_header_nota_mini_pos')
        ->where('is_active', true)
        ->orderBy('order_number')
        ->get();

$jual = DB::table('t_jual_produk')
    ->leftJoin(
        'm_customer',
        'm_customer.customer_id',
        '=',
        't_jual_produk.customer_id'
    )
    ->leftJoin(
        'm_kartu',
        'm_kartu.kartu_id',
        '=',
        't_jual_produk.kartu_id'
    )
    ->where(
        't_jual_produk.jual_id',
        $jualId
    )
    ->select(
        't_jual_produk.*',
        'm_customer.nama_customer',
        'm_kartu.nama_kartu'
    )
    ->first();

    $detail = DB::table('t_item_jual_produk')
        ->join(
            'm_produk',
            'm_produk.produk_id',
            '=',
            't_item_jual_produk.produk_id'
        )
        ->where(
            't_item_jual_produk.jual_id',
            $jualId
        )
        ->select(
            't_item_jual_produk.*',
            'm_produk.nama_produk'
        )
        ->get();

    return view(
        'kasir.struk',
        compact(
            'header',
            'jual',
            'detail'
        )
    );
}

public function printText($jualId)
{
    $jual = DB::table('t_jual_produk')
        ->leftJoin(
            'm_customer',
            'm_customer.customer_id',
            '=',
            't_jual_produk.customer_id'
        )
        ->leftJoin(
            'm_kartu',
            'm_kartu.kartu_id',
            '=',
            't_jual_produk.kartu_id'
        )
        ->where('jual_id', $jualId)
        ->select(
            't_jual_produk.*',
            'm_customer.nama_customer',
            'm_kartu.nama_kartu'
        )
        ->first();

    $detail = DB::table('t_item_jual_produk')
        ->join(
            'm_produk',
            'm_produk.produk_id',
            '=',
            't_item_jual_produk.produk_id'
        )
        ->where('jual_id', $jualId)
        ->select(
            'm_produk.nama_produk',
            't_item_jual_produk.jumlah',
            't_item_jual_produk.harga_jual'
        )
        ->get();

    $kembalian = 0;

    if ($jual->bayar_tunai > 0) {
        $kembalian =
            $jual->bayar_tunai -
            $jual->total_nota;

        if ($kembalian < 0) {
            $kembalian = 0;
        }
    }

    // metode pembayaran
    $metodePembayaran = 'TUNAI';

    if ($jual->bayar_kartu > 0) {
        $metodePembayaran =
            $jual->nama_kartu ?? 'KARTU / QRIS';
    }

    if (
        $jual->customer_id &&
        $jual->tanggal_tempo
    ) {
        $metodePembayaran = 'PIUTANG';
    }

    $text  = "\n";

    $text .= "      PATRAMEDIKA RSPB\n";
    $text .= "Jl. Jendral Sudirman No 1\n";
    $text .= "       Balikpapan\n";
    $text .= "       081142311922\n";

    $text .= "------------------------------\n";

    $text .= "Nota : {$jual->nota}\n";
    $text .= "Tgl  : {$jual->tanggal}\n";
    $text .= "Kasir: " .
        session('nama_pengguna') .
        "\n";

    $text .= "Cust : " .
        ($jual->nama_customer ?? 'UMUM') .
        "\n";

    $text .= "Bayar: " .
        $metodePembayaran .
        "\n";

    $text .= "------------------------------\n";

    foreach ($detail as $item)
    {
        $subtotal =
            $item->jumlah *
            $item->harga_jual;

        $text .=
            $item->nama_produk .
            "\n";

        $text .=
            number_format(
                $item->jumlah,
                0,
                ',',
                '.'
            ) .
            " x " .
            number_format(
                $item->harga_jual,
                0,
                ',',
                '.'
            ) .
            " = " .
            number_format(
                $subtotal,
                0,
                ',',
                '.'
            ) .
            "\n";
    }

    $text .=
        "------------------------------\n";

    $text .=
        "TOTAL      " .
        number_format(
            $jual->total_nota,
            0,
            ',',
            '.'
        ) .
        "\n";

    if ($jual->bayar_tunai > 0)
    {
        $text .=
            "TUNAI      " .
            number_format(
                $jual->bayar_tunai,
                0,
                ',',
                '.'
            ) .
            "\n";

        $text .=
            "KEMBALI    " .
            number_format(
                $kembalian,
                0,
                ',',
                '.'
            ) .
            "\n";
    }

    if ($jual->bayar_kartu > 0)
    {
        $text .=
            "KARTU      " .
            number_format(
                $jual->bayar_kartu,
                0,
                ',',
                '.'
            ) .
            "\n";
    }

    $text .=
        "------------------------------\n";

    $text .=
        "       TERIMA KASIH\n";

    $text .=
        " Barang yang sudah dibeli\n";

    $text .=
        " tidak dapat ditukar\n";

    $text .=
        " atau dikembalikan\n\n\n";

    return response($text);
}

public function checkout(Request $request)
{
    try {

        DB::beginTransaction();

        $jualId = (string) Str::uuid();

        $userId = session('pengguna_id');

        $nota = date('YmdHis');


$isPiutang = $request->is_piutang;

$grandTotal = (float) $request->total_pelunasan;

$totalBayar =
    (float) $request->bayar_tunai +
    (float) $request->bayar_kartu;

// jika piutang wajib pilih customer
if (
    $isPiutang &&
    empty($request->customer_id)
)
{
    return response()->json([
        'success' => false,
        'message' =>
            'Pilih customer terlebih dahulu'
    ], 422);
}

// validasi pembayaran hanya untuk non-piutang
if (
    !$isPiutang &&
    $totalBayar < $grandTotal
)
{
    return response()->json([
        'success' => false,
        'message' =>
            'Nominal pembayaran kurang dari Grand Total'
    ], 422);
}

// tidak boleh dua metode sekaligus
if (
    $request->bayar_tunai > 0 &&
    $request->bayar_kartu > 0
)
{
    return response()->json([
        'success' => false,
        'message' =>
            'Pilih salah satu pembayaran: Tunai atau Kartu/QRIS'
    ], 422);
}

// bayar kartu wajib pilih kartu
if (
    $request->bayar_kartu > 0 &&
    empty($request->kartu_id)
)
{
    return response()->json([
        'success' => false,
        'message' =>
            'Pilih jenis kartu terlebih dahulu'
    ], 422);
}
// HEADER

$tanggalTempo = $request->is_piutang
    ? now()->addDays(30)->toDateString()
    : null;

DB::table('t_jual_produk')->insert([



    'jual_id'          => $jualId,
    'pengguna_id'      => $userId,
    'customer_id'      => $request->customer_id,
    'nota'             => $nota,
    'tanggal'          => now()->toDateString(),

    'tanggal_tempo'    => $tanggalTempo,

    'ppn'              => $request->ppn,
    'diskon'           => $request->diskon,

    'total_nota'       => $request->total_nota,

'total_pelunasan'  => $request->is_piutang
    ? 0
    : $request->total_pelunasan,

    'bayar_tunai'      => $request->bayar_tunai,
    'bayar_kartu'      => $request->bayar_kartu,

    'kartu_id'         => $request->kartu_id,
    'nomor_kartu'      => $request->nomor_kartu,

'mesin_id'         => session('mesin_id'),
'shift_id'         => session('shift_id'),
'cabang_id'        => session('cabang_id'),

'tanggal_sistem'   => now()
]);


        // DETAIL
        foreach ($request->items as $item)
        {
            DB::table('t_item_jual_produk')->insert([

                'item_jual_id'   => (string) Str::uuid(),

                'jual_id'        => $jualId,

                'pengguna_id'    => $userId,

                'produk_id'      => $item['produk_id'],

                'harga_beli' => $item['harga_beli'] ?? 0,

                'harga_jual'     => $item['harga_jual'],

                'jumlah'         => $item['jumlah'],

                'diskon'         => 0,

                'jumlah_retur'   => 0,

                'tanggal_sistem' => now()

            ]);

            // KURANGI STOK GUDANG
            DB::table('m_produk')
                ->where(
                    'produk_id',
                    $item['produk_id']
                )
                ->decrement(
                    'stok_gudang',
                    $item['jumlah']
                );
        }

//         if ($request->is_piutang)
// {
//     $pembayaranPiutangId =
//         (string) Str::uuid();

//     DB::table(
//         't_pembayaran_piutang_produk'
//     )->insert([

//         'pembayaran_piutang_id'
//             => $pembayaranPiutangId,

//         'customer_id'
//             => $request->customer_id,

//         'pengguna_id'
//             => $userId,

//         'tanggal'
//             => now()->toDateString(),

//         'nota'
//             => $nota,

//         'is_tunai'
//             => false,

//         'tanggal_sistem'
//             => now()

//     ]);

//     DB::table(
//         't_item_pembayaran_piutang_produk'
//     )->insert([

//         'item_pembayaran_piutang_id'
//             => (string) Str::uuid(),

//         'pembayaran_piutang_id'
//             => $pembayaranPiutangId,

//         'jual_id'
//             => $jualId,

//         'nominal'
//     => $request->total_nota,

//         'keterangan'
//             => 'Piutang POS Web',

//         'tanggal_sistem'
//             => now()

//     ]);
// }

        DB::commit();

        return response()->json([
            'success' => true,
            'jual_id' => $jualId
        ]);

    } catch (\Exception $e) {

    DB::rollBack();

    return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
        'user_id' => $userId,
        'mesin_id' => session('mesin_id'),
        'cabang_id' => session('cabang_id')
    ], 500);
}
}
}