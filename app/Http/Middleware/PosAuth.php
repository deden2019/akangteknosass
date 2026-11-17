<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PosAuth
{
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        // 1. Cek apakah user sudah login (session pengguna_id ada)
        if (!session()->has('pengguna_id')) {
            return redirect('/login');
        }

        // 2. Jika middleware dipanggil dengan batasan role tertentu (misal: PosAuth:Administrator,Owner)
        if (!empty($allowedRoles)) {
            $roleId = session('role_id');

            // Ambil nama_role dari database berdasarkan role_id di session
            $namaRole = DB::table('public.m_role')
                ->where('role_id', $roleId)
                ->value('nama_role');

            // Jika nama role user tidak ada dalam daftar yang diizinkan, tolak akses (403)
            if (!in_array($namaRole, $allowedRoles)) {
                abort(403, 'Unauthorized access to this section.');
            }
        }

        return $next($request);
    }
}