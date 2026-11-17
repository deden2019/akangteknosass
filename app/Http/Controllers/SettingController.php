<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    // Halaman Utama Menu Setting
    public function index()
    {
        return view('setting.index');
    }

    // Halaman Shift Operasional
    public function shift()
    {
        $listOfShift = DB::table('m_shift')->orderBy('jam_mulai', 'asc')->get();
        return view('setting.shift', compact('listOfShift'));
    }

    // Halaman Metode Pembayaran / Kartu
    public function kartu()
    {
        $listOfKartu = DB::table('m_kartu')->orderBy('nama_kartu', 'asc')->get();
        return view('setting.kartu', compact('listOfKartu'));
    }

    // Proses Tambah Kartu / Metode Pembayaran
    public function storeKartu(Request $request)
    {
        $request->validate([
            'nama_kartu' => 'required|string|max:50',
            'is_debit' => 'required|boolean',
        ]);

        DB::table('m_kartu')->insert([
            'kartu_id' => (string) Str::uuid(), // Menghasilkan UUID otomatis untuk PostgreSQL
            'nama_kartu' => $request->nama_kartu,
            'is_debit' => $request->is_debit,
        ]);

        return redirect()->route('setting.kartu')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    // Proses Update Kartu / Metode Pembayaran
    public function updateKartu(Request $request, $id)
    {
        $request->validate([
            'nama_kartu' => 'required|string|max:50',
            'is_debit' => 'required|boolean',
        ]);

        DB::table('m_kartu')
            ->where('kartu_id', $id)
            ->update([
                'nama_kartu' => $request->nama_kartu,
                'is_debit' => $request->is_debit,
            ]);

        return redirect()->route('setting.kartu')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    // Proses Hapus Kartu / Metode Pembayaran
    public function destroyKartu($id)
    {
        DB::table('m_kartu')->where('kartu_id', $id)->delete();

        return redirect()->route('setting.kartu')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}