<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        /* Pengaturan Dasar untuk Kertas Thermal 58mm */
body {
    font-family: 'Courier New', Courier, monospace;
    font-size: 15px;
    width: 58mm;
    margin: 0 auto;
    padding: 5px;
    color: #000;
    background: #fff;
    line-height: 1.5;
}

.bold {
    font-weight: 700;
}

table td {
    padding: 3px 0;
    vertical-align: top;
    font-size: 15px;
}

.item-row {
    margin-bottom: 6px;
}

.item-detail {
    display: flex;
    justify-content: space-between;
    margin-top: 3px;
    font-size: 15px;
}

.footer {
    margin-top: 10px;
    font-size: 13px;
}

        /* Otomatis Print saat dibuka */
@media print {

    body {
        zoom: 1.15;
        width: 58mm;
    }

}
    </style>
</head>
<body>

    <!-- Header / Nama Toko -->
    <div class="center bold" style="font-size: 18px; margin-bottom: 8px;">
        @foreach($header as $h)
            <div>{{ $h->keterangan }}</div>
        @endforeach
    </div>

    <div class="line"></div>

    <!-- Informasi Nota, Tanggal, dan Kasir -->
    <div class="info-transaksi">
        <table>
            <tr>
                <td style="width: 35%;">Nota</td>
                <td style="width: 5%;">:</td>
                <td style="width: 60%;">{{ $jual->nota }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $jual->tanggal }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td>{{ session('nama_pengguna') }}</td>
            </tr>
     <tr>
    <td>Customer</td>
    <td>:
        {{ $jual->nama_customer ?? 'UMUM' }}
    </td>
</tr>

<tr>
    <td>Pembayaran</td>
    <td>:

        @if($jual->bayar_tunai > 0)

            TUNAI

@elseif($jual->bayar_kartu > 0)

    {{ $jual->nama_kartu ?? 'KARTU / QRIS' }}

        @elseif($jual->customer_id)

            PIUTANG

        @else

            TIDAK DIKETAHUI

        @endif

    </td>
</tr>
        </table>
    </div>

    <div class="line"></div>

    <!-- Daftar Produk yang Dibeli -->
    @foreach($detail as $item)
        <div class="item-row">
            <div class="bold">{{ $item->nama_produk }}</div>
            <div class="item-detail">
                <span>{{ $item->jumlah }} x {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                <span class="right">
                    {{ number_format($item->jumlah * $item->harga_jual, 0, ',', '.') }}
                </span>
            </div>
        </div>
    @endforeach

    <div class="line"></div>

    @php
    $kembalian = 0;

    if ($jual->bayar_tunai > 0) {
        $kembalian = $jual->bayar_tunai - $jual->total_nota;

        if ($kembalian < 0) {
            $kembalian = 0;
        }
    }
@endphp


    <!-- Rincian Total, Tunai, Kartu -->
    <table>
        <tr>
    <td class="bold" style="font-size:18px">
        Total
    </td>
    <td class="right bold" style="font-size:18px">
        {{ number_format($jual->total_nota, 0, ',', '.') }}
    </td>
</tr>

        @if($jual->bayar_tunai > 0)

<tr>
    <td>Tunai</td>
    <td class="right">
        {{ number_format($jual->bayar_tunai, 0, ',', '.') }}
    </td>
</tr>

<tr>
    <td>Kembalian</td>
    <td class="right">
        {{ number_format($kembalian, 0, ',', '.') }}
    </td>
</tr>

@endif
@if($jual->bayar_kartu > 0)
<tr>
    <td>Kartu / QRIS</td>
    <td class="right">
        {{ number_format($jual->bayar_kartu, 0, ',', '.') }}
    </td>
</tr>
@endif

@if($jual->customer_id && $jual->tanggal_tempo)
<tr>
    <td>Piutang</td>
    <td class="right">
        {{ number_format($jual->total_nota, 0, ',', '.') }}
    </td>
</tr>
@endif

    </table>

    <div class="line"></div>

    <!-- Footer / Ucapan Terima Kasih -->
    <div class="center footer">
        <span class="bold">TERIMA KASIH</span><br>
        <span>Barang yang sudah dibeli</span><br>
        <span>tidak dapat ditukar/dikembalikan</span>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>