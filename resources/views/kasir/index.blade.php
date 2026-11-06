<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">

<meta name="theme-color" content="#03ac0e">

<meta
    name="mobile-web-app-capable"
    content="yes">

<meta
    name="apple-mobile-web-app-capable"
    content="yes">

<meta
    name="apple-mobile-web-app-status-bar-style"
    content="default">


    <title>Mesin Kasir POS - Mobile Friendly</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        toped: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#03ac0e', // Hijau Utama Tokopedia
                            600: '#02920b',
                            700: '#027709',
                        }
                    }
                }
            }
        }
    </script>

    <style>
#cart-list::-webkit-scrollbar {
    width: 8px;
}

#cart-list::-webkit-scrollbar-track {
    background: #f1f5f9;
}

#cart-list::-webkit-scrollbar-thumb {
    background: #03ac0e;
    border-radius: 9999px;
}
</style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen flex flex-col overflow-y-auto">

    <!-- TOPBAR -->
    <header class="bg-toped-500 text-white shadow-md z-30 flex-none">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="/dashboard" class="p-1.5 hover:bg-white/20 rounded-lg transition">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-base sm:text-lg font-bold">Mesin Kasir POS</h1>
            </div>
            
            <!-- TOMBOL REFRESH & INFO CABANG -->
            <div class="flex items-center space-x-2">
                <button
                    onclick="hardRefresh()"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white text-green-700 text-xs sm:text-sm font-bold shadow-sm hover:bg-green-50 transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Refresh</span>
                </button>

                <div class="flex items-center space-x-2 text-xs font-medium bg-toped-600 px-3 py-1.5 rounded-xl border border-toped-700">
                    <i data-lucide="store" class="w-4 h-4"></i>
                    <span>{{ session('cabang_id') ?? 'PUSAT' }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
<main class="flex-1 max-w-7xl w-full mx-auto p-2 sm:p-4 grid grid-cols-1 md:grid-cols-12 gap-3 relative pb-16 md:pb-4">
        
        <!-- KOLOM KIRI: KATALOG PRODUK & PENCARIAN (Span 7) -->
        <section
    id="catalog-section"
    class="flex flex-col bg-white rounded-2xl border border-slate-200 shadow-sm min-h-0 md:col-span-7">

            
            <!-- Search Bar / Barcode Input -->
            <div class="p-3 border-b border-slate-100 bg-slate-50 flex-none">
                <div class="relative">
                    <i data-lucide="scan-barcode" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-toped-600"></i>
                    <input 
                        type="text" 
                        id="barcode-input"
                        placeholder="Scan barcode atau ketik nama produk..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:border-toped-500 focus:ring-1 focus:ring-toped-500 shadow-sm transition"
                        autofocus
                    >
                </div>
            </div>

            <!-- Grid Produk -->
            <div class="flex-1 min-h-0 p-3 overflow-y-auto bg-slate-50/50">
                <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    <div class="col-span-full py-12 text-center text-slate-400 text-xs">
                        Memuat data produk...
                    </div>
                </div>
            </div>

        </section>

        <!-- KOLOM KANAN: KERANJANG, CUSTOMER & CHECKOUT (Span 5) -->
<section id="cart-section"
    class="hidden flex-col bg-white rounded-2xl border border-slate-200 shadow-sm
           fixed inset-2 z-50
           md:relative md:flex
           md:col-span-5 md:inset-auto">
            
            <!-- Header Keranjang -->
            <div class="p-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between flex-none">
                <div class="flex items-center space-x-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-toped-600"></i>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-800">Keranjang Belanja</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="clearCart()" class="text-[11px] text-red-500 hover:text-red-600 font-semibold flex items-center gap-1 transition">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Kosongkan
                    </button>
                    <!-- Tombol Close khusus di HP -->
                    <button onclick="toggleMobileCart()" class="md:hidden p-1.5 text-slate-500 hover:bg-slate-200 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Section Customer -->
            <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex-none space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-toped-600"></i> Pelanggan
                    </label>
                </div>

                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        id="customer-search"
                        placeholder="Cari nama atau telepon customer..."
                        class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-toped-500 focus:ring-1 focus:ring-toped-500 transition shadow-sm">
                </div>

                <div id="customer-result" class="max-h-32 overflow-auto space-y-1 hidden bg-white border border-slate-100 rounded-xl p-1 shadow-inner">
                    <!-- Hasil pencarian customer -->
                </div>

                <div class="flex items-center justify-between p-2 bg-toped-50/50 rounded-xl border border-toped-100">
                    <span class="text-[11px] font-medium text-slate-500">Terpilih:</span>
                    <span id="selected-customer-name" class="font-bold text-xs text-toped-600">
                        UMUM
                    </span>
                </div>
            </div>

            <!-- List Item Keranjang -->
<div id="cart-list"
     class="flex-1 min-h-0 p-3 pb-4 overflow-y-auto divide-y divide-slate-100 bg-white overscroll-contain">

                <div class="py-16 text-center text-slate-400 text-xs">
                    Keranjang masih kosong.<br>Silakan pilih produk di katalog.
                </div>
            </div>

            <!-- Footer Checkout -->
            <div
    class="p-3.5 border-t border-slate-100 bg-slate-50 space-y-2.5 flex-none sticky bottom-0">
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Total Item</span>
                        <span id="total-qty" class="font-bold text-slate-700">0 Pcs</span>
                    </div>
                    <div class="flex justify-between text-sm sm:text-base font-extrabold text-slate-900 pt-1 border-t border-slate-200">
                        <span>Total Tagihan</span>
                        <span id="grand-total" class="text-toped-600">Rp 0</span>
                    </div>
                </div>

                <button 
                    onclick="processCheckout()"
                    class="w-full bg-toped-500 hover:bg-toped-600 active:scale-[0.98] text-white font-bold py-2.5 rounded-xl shadow-md transition flex items-center justify-center gap-2 text-sm">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>Bayar Sekarang</span>
                </button>
            </div>

        </section>

    </main>

    <!-- FLOATING CART BUTTON (Khusus Tampilan Handphone di Bawah) -->
    <div
    id="mobile-cart-button"
    class="md:hidden fixed bottom-3 left-4 right-4 z-40">
        <button onclick="toggleMobileCart()" class="w-full bg-toped-500 text-white py-3 px-4 rounded-2xl shadow-xl flex items-center justify-between font-bold text-sm active:scale-95 transition">
            <div class="flex items-center gap-2">
                <div class="bg-white/20 p-1.5 rounded-xl">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
                <span id="mobile-cart-count">0 Item</span>
            </div>
            <span id="mobile-cart-total" class="text-base font-extrabold">Rp 0</span>
        </button>
    </div>

<div
    id="payment-modal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-2 overflow-y-auto">

<div
    class="bg-white rounded-2xl w-full max-w-md p-5 my-4 max-h-[95vh] overflow-y-auto relative">


    <button
    onclick="closePaymentModal()"
    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600">

    <i data-lucide="x" class="w-4 h-4"></i>

</button>

        <h2 class="text-lg font-bold mb-4">
            Pembayaran
        </h2>

        <div class="mb-3">
    <label class="text-sm text-slate-500">
        Customer
    </label>

                <div
                    id="payment-customer"
                    class="font-bold text-toped-600">

                    UMUM

                </div>
            </div>

    <div class="bg-slate-50 rounded-xl p-3 mb-3">

    <div class="flex justify-between">
        <span>Total Nota</span>
        <span id="payment-total-nota">
            Rp 0
        </span>
    </div>

    <div class="flex justify-between font-bold text-lg text-toped-600 mt-2">
        <span>Grand Total</span>
        <span id="grand-total-modal">
            Rp 0
        </span>
    </div>

</div>

        <!-- Diskon -->
        <div class="mb-3">
            <label>Diskon</label>
            <input
                type="number"
                id="diskon"
                value="0"
                class="w-full border rounded-lg p-2">
        </div>

        <!-- PPN -->
        <div class="mb-3">
            <label>PPN (%)</label>
            <input
                type="number"
                id="ppn"
                value="0"
                class="w-full border rounded-lg p-2">
        </div>

<div class="mb-3">

    <label class="flex items-center gap-3 cursor-pointer">

        <input
            type="checkbox"
            id="is-piutang"
            class="w-5 h-5">

        <span class="font-medium text-slate-700">
            Transaksi Piutang
        </span>

    </label>

    <div class="text-xs text-slate-500 mt-1">
        Customer wajib dipilih jika transaksi piutang
    </div>

</div>


        <!-- Bayar Tunai -->
        <div class="mb-3">
            <label>Bayar Tunai</label>

            <input
    type="number"
    id="bayar-tunai"
    value=""
    placeholder="0"
    class="w-full border rounded-lg p-2">
        </div>

        <!-- Bayar Kartu -->
        <div class="mb-3">
            <label>Bayar Kartu</label>

            <input
    type="number"
    id="bayar-kartu"
    value=""
    placeholder="0"
    class="w-full border rounded-lg p-2">
        </div>

        <!-- Jenis Kartu -->
        <div class="mb-3">
            <label>Jenis Kartu</label>

<select
    id="jenis-kartu"
    class="w-full border rounded-lg p-2">
</select>
        </div>

        <!-- No Kartu -->
        <div class="mb-3">
            <label>Referensi Pembayaran</label>

            <input
                type="text"
                id="nomor-kartu"
                class="w-full border rounded-lg p-2">
        </div>

        <div class="bg-green-50 border border-green-200 rounded-xl p-3">

    <div class="text-sm text-slate-500">
        Kembalian
    </div>

    <div
        id="kembalian"
        class="text-3xl font-bold text-green-600">

        Rp 0

    </div>

</div>


        <div class="flex gap-2">

            <button
                onclick="closePaymentModal()"
                class="flex-1 bg-red-500 text-white py-2 rounded-lg">

                Batal

            </button>

            <button
                onclick="saveTransaction()"
                class="flex-1 bg-green-600 text-white py-2 rounded-lg">

                Simpan

            </button>

        </div>

    </div>
</div>



<div
    id="toast-error"
    class="hidden fixed top-5 right-5 z-[9999]">

    <div
        class="bg-red-500 text-white px-5 py-4 rounded-xl shadow-xl min-w-[300px]">

        <div class="font-bold">
            ⚠️ Transaksi Gagal
        </div>

        <div
            id="toast-error-message"
            class="text-sm mt-1">
        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/kasir.js"></script>

<script>
// if ('serviceWorker' in navigator)
// {
//     navigator.serviceWorker
//         .register('/sw.js')
//         .then(() => {
//             console.log(
//                 'Service Worker Registered'
//             );
//         });
// }

if ('serviceWorker' in navigator)
{
    navigator.serviceWorker
        .getRegistrations()
        .then(function(registrations){

            registrations.forEach(function(reg){

                reg.unregister();

            });

        });
}

// Fungsi untuk tombol Refresh
function hardRefresh()
{
    location.href =
        '/kasir?r=' +
        Date.now();
}

</script>


<div
id="rotate-warning"
    class="hidden fixed inset-0 bg-white z-[99999] flex items-center justify-center">

    <div class="text-center">

        <div class="text-6xl">
            🔄
        </div>

        <h2 class="text-xl font-bold mt-3">
            Putar Perangkat
        </h2>

        <p class="text-slate-500">
            Mesin Kasir hanya dapat digunakan
            dalam mode landscape
        </p>

    </div>

</div>

@if(session('error'))

<script>

Swal.fire({
    icon: 'warning',
    title: 'Akses Ditolak',
    text: "{{ session('error') }}"
});

</script>

@endif



<script>
function checkOrientation()
{
    const el =
        document.getElementById(
            'rotate-warning'
        );

    if (
        window.innerWidth < 1024 &&
        window.innerHeight >
        window.innerWidth
    ) {
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}

window.addEventListener(
    'resize',
    checkOrientation
);

window.addEventListener(
    'orientationchange',
    checkOrientation
);

checkOrientation();
</script>
</body>
</html>