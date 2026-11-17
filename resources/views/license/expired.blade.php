<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisensi Berakhir</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl p-8 text-center">

        <div class="text-red-500 text-6xl mb-4">
            ⚠
        </div>

        <h1 class="text-2xl font-bold text-slate-800">
            Lisensi Aplikasi Berakhir
        </h1>

        <p class="mt-4 text-slate-600">
            Masa aktif lisensi aplikasi telah berakhir.
        </p>

        <div class="mt-6 p-4 bg-slate-50 rounded-xl">

            <div class="text-xs text-slate-500">
                Nama Perusahaan
            </div>

            <div class="font-semibold text-slate-800">
                {{ $license->nama_perusahaan ?? '-' }}
            </div>

            <div class="mt-3 text-xs text-slate-500">
                Tanggal Expired
            </div>

            <div class="font-semibold text-red-600">
                {{ \Carbon\Carbon::parse($license->tgl_expired)->format('d-m-Y') }}
            </div>

        </div>

        <div class="mt-6 text-sm text-slate-500">
            Hubungi administrator atau AKANGTEKNO untuk memperpanjang lisensi aplikasi.
        </div>

        <div class="mt-2 font-semibold text-emerald-600">
            support@akangtekno.id
        </div>

    </div>

</body>

</html>
