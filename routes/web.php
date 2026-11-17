<?php

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KasirController;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\LaporanShiftOperasionalController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root URL (Mengatasi error 404 saat akses domain utama)
Route::get('/', function () {
    return redirect('/login');
});

// Autentikasi
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

// Group Route yang Membutuhkan Middleware Login
Route::middleware([
    'license',
    'pos.auth'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/produk', [ProdukController::class, 'index']);

    Route::get('/produk', [ProdukController::class, 'index']);

Route::get(
    '/produk/tambah',
    [ProdukController::class, 'create']
)->name('produk.create');

Route::post(
    '/produk/simpan',
    [ProdukController::class, 'store']
)->name('produk.store');

Route::get(
    '/produk/edit/{id}',
    [ProdukController::class, 'edit']
)->name('produk.edit');

Route::put(
    '/produk/update/{id}',
    [ProdukController::class, 'update']
)->name('produk.update');

Route::post(
    '/produk/hapus/{id}',
    [ProdukController::class, 'destroy'
])->name('produk.destroy');


    Route::get('/customer', [CustomerController::class, 'index']);

    Route::get(
        '/laporan/shift-operasional',
        [LaporanShiftOperasionalController::class, 'index']
    );

    Route::post(
        '/laporan/shift-operasional',
        [LaporanShiftOperasionalController::class, 'generate']
    );

    Route::get(
    '/laporan/penjualan-produk',
    [LaporanPenjualanController::class, 'index']
)->name('laporan.penjualan');
});




Route::middleware([
    'license',
    'pos.auth:Administrator,Owner'
])->group(function () {

    Route::get(
        '/setting',
        [SettingController::class, 'index']
    )->name('setting.index');

    Route::get(
        '/setting/shift',
        [SettingController::class, 'shift']
    )->name('setting.shift');

    Route::get(
        '/setting/kartu',
        [SettingController::class, 'kartu']
    )->name('setting.kartu');

    Route::post(
        '/setting/kartu/simpan',
        [SettingController::class, 'storeKartu']
    )->name('setting.kartu.store');

    Route::put(
        '/setting/kartu/update/{id}',
        [SettingController::class, 'updateKartu']
    )->name('setting.kartu.update');

    Route::post(
        '/setting/kartu/hapus/{id}',
        [SettingController::class, 'destroyKartu']
    )->name('setting.kartu.destroy');
});


Route::get(
    '/kasir',
    [KasirController::class, 'index']
)->middleware(['license','pos.auth']);

Route::post(
    '/kasir/checkout',
    [KasirController::class, 'checkout']
)->middleware(['license','pos.auth']);


// API PRODUK
// Route::get('/api/produk-kasir', function () {

//     \Log::info('API PRODUK DIPANGGIL');

//     $data = Produk::select(
//         'produk_id',
//         'kode_produk',
//         'nama_produk',
//         'harga_jual',
//         'stok_gudang'
//     )
//     ->limit(100)
//     ->get();

//     \Log::info(
//         'JUMLAH PRODUK : ' .
//         $data->count()
//     );

//     return $data;
// });

Route::get('/api/produk-kasir', function () {

    $data = Produk::select(
        'produk_id',
        'kode_produk',
        'nama_produk',
        'harga_jual',
        'stok_gudang'
    )
    ->limit(100)
    ->get();

    return response()->json($data);
});

Route::get('/api/produk-barcode/{kode}', function ($kode) {

    return Produk::select(
        'produk_id',
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok_gudang'
    )
    ->where('kode_produk', $kode)
    ->first();

});

Route::get('/api/cari-customer', function (Request $request) {

    $keyword = trim($request->q);

    return Customer::select(
        'customer_id',
        'nama_customer'
    )
    ->where(
        'nama_customer',
        'ILIKE',
        "%{$keyword}%"
    )
    ->orderBy('nama_customer')
    ->limit(20)
    ->get();

});


Route::get('/api/kartu', function () {

    return DB::table('m_kartu')
        ->select(
            'kartu_id',
            'nama_kartu'
        )
        ->orderBy('nama_kartu')
        ->get();

})->middleware('pos.auth');

Route::get(
    '/kasir/struk/{jualId}',
    [KasirController::class, 'struk']
);

Route::get(
    '/kasir/print/{jualId}',
    [KasirController::class, 'printText']
);



Route::get('/api/cari-produk', function (Request $request) {

    $keyword = trim($request->q);

    return Produk::select(
        'produk_id',
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok_gudang'
    )
    ->where(function($q) use ($keyword){

        $q->where(
            'nama_produk',
            'ILIKE',
            "%{$keyword}%"
        )
        ->orWhere(
            'kode_produk',
            'ILIKE',
            "%{$keyword}%"
        );

    })
    ->orderBy('nama_produk')
    ->limit(50)
    ->get();

});
