<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $namaRole = DB::table('m_role')
            ->where(
                'role_id',
                session('role_id')
            )
            ->value('nama_role');

        return view(
            'dashboard',
            compact('namaRole')
        );
    }
}