<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produk;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mengirim data produk yang stoknya di bawah atau sama dengan minimal stok ke semua layout
        View::composer('*', function ($view) {
            // Ambil produk di mana stok_gudang <= minimal_stok (sesuaikan kolom jika pakai minimal_stok_gudang)
            $produkMenipis = Produk::whereColumn('stok_gudang', '<=', 'minimal_stok')->get();
            
            $view->with('produkMenipis', $produkMenipis);
        });
    }
}