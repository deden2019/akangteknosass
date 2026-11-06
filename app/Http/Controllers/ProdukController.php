<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->q ?? '');
        
        // 1. Ambil nilai per_page dari request, jika kosong gunakan default (misal 10)
        $perPage = $request->input('per_page', 10);

        $produk = Produk::query();

        if ($keyword != '') {
            $produk->where(function ($q) use ($keyword) {
                // Perhatikan: Gunakan 'ilike' atau 'like' secara konsisten 
                // (PostgreSQL aman menggunakan ilike untuk pencarian case-insensitive)
                $q->where('kode_produk', 'ilike', "%{$keyword}%")
                  ->orWhere('nama_produk', 'ilike', "%{$keyword}%");
            });
        }

        $produk = $produk
            ->orderBy('nama_produk')
            ->paginate($perPage) // 2. Masukkan variabel $perPage ke sini
            ->withQueryString();

        return view(
            'produk.index',
            compact('produk', 'keyword')
        );
    }

public function create()
{
    if (!in_array(session('nama_role'), ['Administrator', 'Owner'])) {
        abort(403, 'Akses ditolak');
    }

    return view('produk.tambah');
}

public function store(Request $request)
{
     if (!in_array(session('nama_role'), ['Administrator', 'Owner'])) {
        abort(403, 'Akses ditolak');
    }
    $request->validate([
    'kode_produk' => 'required',
    'nama_produk' => 'required',
    'satuan'      => 'required',
]);

    Produk::create([
    'produk_id'   => Str::uuid()->toString(),
    'kode_produk' => $request->kode_produk,
    'nama_produk' => $request->nama_produk,
    'satuan'      => $request->satuan,
    'harga_beli'  => $request->harga_beli,
    'harga_jual'  => $request->harga_jual,
    'stok_gudang' => $request->stok_gudang,
]);

    return redirect('/produk')
        ->with('success', 'Produk berhasil disimpan');
}

public function edit($id)
{
    if (!in_array(session('nama_role'), ['Administrator', 'Owner'])) {
        abort(403, 'Akses ditolak');
    }

    $produk = Produk::findOrFail($id);

    return view('produk.edit', compact('produk'));
}

public function update(Request $request, $id)
{
    // dd($request->all());
    if (!in_array(session('nama_role'), ['Administrator', 'Owner'])) {
        abort(403, 'Akses ditolak');
    }

    $request->validate([
    'kode_produk' => 'required',
    'nama_produk' => 'required',
    'satuan'      => 'required',
]);

    $produk = Produk::findOrFail($id);

    $produk->update([
    'kode_produk' => $request->kode_produk,
    'nama_produk' => $request->nama_produk,
    'satuan'      => $request->satuan,
    'harga_beli'  => $request->harga_beli,
    'harga_jual'  => $request->harga_jual,
    'stok_gudang' => $request->stok_gudang,
]);

    return redirect('/produk')
        ->with('success', 'Produk berhasil diupdate');
}

public function destroy($id)
{
    if (!in_array(session('nama_role'), ['Administrator', 'Owner'])) {
        abort(403, 'Akses ditolak');
    }

    $produk = Produk::findOrFail($id);

    $produk->delete();

    return redirect('/produk')
        ->with('success', 'Produk berhasil dihapus');
}
}
