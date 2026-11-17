<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckLicense
{
    public function handle($request, Closure $next)
    {
        $license = DB::table('m_lisensi')
            ->where('is_aktif', true)
            ->first();

        if (!$license)
        {
            return response()->view(
                'license.expired',
                [
                    'message' => 'Lisensi tidak ditemukan'
                ]
            );
        }

        if (
            !$license->is_permanent &&
            Carbon::today()->gt(
                Carbon::parse($license->tgl_expired)
            )
        )
        {
            return response()->view(
    'license.expired',
    [
        'license' => $license
    ]
);
        }

        return $next($request);
    }
}
