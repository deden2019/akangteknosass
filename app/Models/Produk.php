<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'm_produk';

    protected $primaryKey = 'produk_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    // TAMBAHKAN KODE INI:
 protected $fillable = [
    'produk_id',
    'kode_produk',
    'nama_produk',
    'satuan',
    'harga_beli',
    'harga_jual',
    'diskon',
    'stok',
    'stok_gudang',
    'minimal_stok_gudang',
    'golongan_id',
    'is_aktif',
];
}