<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private const SECURITY_CODE = 'BhGr7YwZpdX7ubFuZCuU';

    public function index()
{
    $shift = DB::table('m_shift')
        ->where('is_active', true)
        ->orderBy('nama_shift')
        ->get();

    return view(
        'auth.login',
        compact('shift')
    );
}

    public function authenticate(Request $request)
    {
            $request->validate([
                'username'   => 'required',
                'password'   => 'required',
                'shift_id'   => 'required',
                'saldo_awal' => 'nullable|numeric|gt:0'
            ]);

        $hash = md5(
            $request->password . self::SECURITY_CODE
        );

        $user = Pengguna::whereRaw(
                    'LOWER(nama_pengguna) = ?',
                    [strtolower($request->username)]
                )
                ->where('pass_pengguna', $hash)
                ->where('is_active', true)
                ->first();

        if (!$user) {
            return back()->with(
                'error',
                'Username atau password salah'
            );
        }

$roleAdmin = DB::table('m_role')
    ->where('nama_role', 'Administrator')
    ->first();

if (
    $roleAdmin &&
    $user->role_id != $roleAdmin->role_id &&
    (float)$request->saldo_awal <= 0
)
{
    return back()
        ->withInput()
        ->with(
            'error',
            'Saldo awal wajib diisi'
        );
}

/*
 * CEK SALDO AWAL
 */
if ((float)$request->saldo_awal <= 0)
{
    return back()
        ->withInput()
        ->with(
            'error',
            'Saldo awal wajib diisi'
        );
}

$mesin = DB::table('t_mesin')
    ->where('pengguna_id', $user->pengguna_id)
    ->where('shift_id', $request->shift_id)
    ->whereDate('tanggal', now())
    ->first();



if (!$mesin)
{
    $mesinId = (string) \Illuminate\Support\Str::uuid();

    DB::table('t_mesin')->insert([
        'mesin_id'    => $mesinId,
        'pengguna_id' => $user->pengguna_id,
        'shift_id'    => $request->shift_id,
        'saldo_awal'  => $request->saldo_awal,
        'tanggal'     => now()->toDateString()
    ]);

    $mesin = DB::table('t_mesin')
        ->where('mesin_id', $mesinId)
        ->first();


}


    $role = DB::table('m_role')
    ->where(
        'role_id',
        $user->role_id
    )
    ->first();
    
session([
    'pengguna_id'   => $user->pengguna_id,
    'nama_pengguna' => $user->nama_pengguna,

    'role_id'       => $user->role_id,
    'nama_role'     => $role->nama_role,

    'cabang_id'     => $user->cabang_id,

    'mesin_id'      => $mesin->mesin_id,
    'shift_id'      => $mesin->shift_id,
    'saldo_awal'    => $mesin->saldo_awal,
]);

        return redirect('/dashboard');
    }

    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }
}
