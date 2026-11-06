<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Shift Operasional</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        toped: {
                            500: '#03ac0e',
                            600: '#02920b',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 min-h-screen">

<header class="bg-toped-500 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-3">

        <a href="/dashboard">
            <i data-lucide="arrow-left"></i>
        </a>

        <h1 class="font-bold text-lg">
            Laporan Shift Operasional
        </h1>

    </div>
</header>

<main class="max-w-7xl mx-auto p-4">

    <form method="POST"
          action="/laporan/shift-operasional"
          class="bg-white rounded-2xl p-4 shadow space-y-4">

        @csrf

        <div>
            <label class="text-sm font-medium">
                Tanggal
            </label>

            <input
                type="date"
                name="tanggal"
                value="{{ date('Y-m-d') }}"
                class="w-full border rounded-xl p-2">
        </div>

        <div>
            <label class="text-sm font-medium">
                Kasir
            </label>

            <select
                name="kasir_id"
                class="w-full border rounded-xl p-2">

                @foreach($kasir as $item)

                    <option value="{{ $item->pengguna_id }}">
                        {{ $item->nama_pengguna }}
                    </option>

                @endforeach

            </select>

        </div>

        <div>
            <label class="text-sm font-medium">
                Shift
            </label>

            <select
                name="shift_id"
                class="w-full border rounded-xl p-2">

                @foreach($shift as $item)

                    <option value="{{ $item->shift_id }}">
                        {{ $item->nama_shift }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="flex gap-2">

    <button
        type="submit"
        class="bg-toped-500 text-white px-4 py-2 rounded-xl">
        Tampilkan
    </button>

    @if(isset($laporan))

    <button
        type="button"
        onclick="print58()"
        class="bg-blue-600 text-white px-4 py-2 rounded-xl">
        Print 58mm
    </button>

    <button
        type="button"
        onclick="print80()"
        class="bg-slate-700 text-white px-4 py-2 rounded-xl">
        Print 80mm
    </button>


    
<div id="thermal-print" class="hidden">

    <div class="text-center">

        <strong>KOPERASI RSPB</strong><br>

        LAPORAN SHIFT OPERASIONAL<br>

        {{ $laporan['nama_kasir'] }}

    </div>

    <hr>

    Periode<br>

    {{ $mulai->format('d/m/Y H:i') }}
    -
    {{ $selesai->format('d/m/Y H:i') }}

    <hr>

    Tunai
    {{ number_format($laporan['tunai'],0,',','.') }}

    <br>

    Piutang
    {{ number_format($laporan['piutang'],0,',','.') }}

    <br><br>

    @foreach($laporan['detail_kartu'] ?? [] as $item)

        {{ $item->nama_kartu }}
        {{ number_format($item->total,0,',','.') }}

        <br>

    @endforeach

    <hr>

    Saldo Awal
    {{ number_format($laporan['saldo_awal'],0,',','.') }}

    <br>

    TOTAL JUAL
    {{ number_format($laporan['total_jual'],0,',','.') }}

</div>


    @endif

</div>

    </form>

        @if(isset($laporan))

<div class="mt-4 bg-white rounded-2xl p-4 shadow">

    <h2 class="font-bold text-lg mb-3">
        Ringkasan Shift
    </h2>

    <div class="mb-4 text-sm text-slate-600">

    <div>
        Kasir :
        <span class="font-semibold">
            {{ $laporan['nama_kasir'] }}
        </span>
    </div>

</div>

@if(isset($mulai) && isset($selesai))

<div class="text-xs text-slate-500 mb-3">
    Periode :
    {{ $mulai->format('d/m/Y H:i') }}
    -
    {{ $selesai->format('d/m/Y H:i') }}
</div>

@endif

    <div class="space-y-2 text-sm">

        <div class="flex justify-between">
            <span>Saldo Awal</span>
            <span>
                Rp {{ number_format($laporan['saldo_awal'],0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between">
            <span>Tunai</span>
            <span>
                Rp {{ number_format($laporan['tunai'],0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between">
            <span>Piutang</span>
            <span>
                Rp {{ number_format($laporan['piutang'],0,',','.') }}
            </span>
        </div>

        <div class="border-t pt-3 mt-3">

    <div class="font-semibold mb-2">
        Non Tunai
    </div>

    @foreach($laporan['detail_kartu'] ?? [] as $kartu)

        <div class="flex justify-between">

            <span>
                {{ $kartu->nama_kartu }}
            </span>

            <span>
                Rp {{ number_format($kartu->total,0,',','.') }}
            </span>

        </div>

    @endforeach

</div>

        <div class="flex justify-between">
            <span>Diskon</span>
            <span>
                Rp {{ number_format($laporan['diskon'],0,',','.') }}
            </span>
        </div>

        <div class="flex justify-between">
            <span>PPN</span>
            <span>
                Rp {{ number_format($laporan['ppn'],0,',','.') }}
            </span>
        </div>

        <hr>

        <div class="flex justify-between font-bold text-green-600 text-base">
            <span>Total Jual</span>
            <span>
                Rp {{ number_format($laporan['total_jual'],0,',','.') }}
            </span>
        </div>

    </div>

</div>

@endif



</main>

<script>
    lucide.createIcons();
</script>
<script src="{{ secure_asset('js/print-shift-operasional.js') }}"></script>
</body>
</html>