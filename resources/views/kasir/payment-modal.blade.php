<div
    id="payment-modal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl w-full max-w-md p-5">

        <h2 class="text-lg font-bold mb-4">
            Pembayaran
        </h2>

        <!-- Total Nota -->
        <div class="mb-3">
            <label class="block text-sm text-slate-500">
                Total Nota
            </label>

            <div
                id="payment-total-nota"
                class="text-xl font-bold text-green-600">
                Rp 0
            </div>
        </div>

        <!-- Diskon -->
        <div class="mb-3">
            <label class="block text-sm mb-1">
                Diskon
            </label>

            <input
                type="number"
                id="diskon"
                value="0"
                class="w-full border rounded-lg p-2">
        </div>

        <!-- PPN -->
        <div class="mb-3">
            <label class="block text-sm mb-1">
                PPN (%)
            </label>

            <input
                type="number"
                id="ppn"
                value="11"
                class="w-full border rounded-lg p-2">
        </div>

        <!-- Grand Total -->
        <div class="mb-4">

            <label class="block text-sm text-slate-500">
                Grand Total
            </label>

            <div
                id="grand-total-modal"
                class="text-2xl font-bold text-blue-600">

                Rp 0

            </div>
        </div>

        <!-- Bayar Tunai -->
        <div class="mb-3">

            <label class="block text-sm mb-1">
                Bayar Tunai
            </label>

            <input
                type="number"
                id="bayar-tunai"
                value="0"
                class="w-full border rounded-lg p-2">

        </div>

        <!-- Bayar Kartu -->

        <div class="mb-3">

            <label class="block text-sm mb-1">
                Bayar Kartu
            </label>

            <input
                type="number"
                id="bayar-kartu"
                value="0"
                class="w-full border rounded-lg p-2">

        </div>

        <!-- Jenis Kartu -->

        <div class="mb-3">

            <label class="block text-sm mb-1">
                Kartu
            </label>

            <select
                id="jenis-kartu"
                class="w-full border rounded-lg p-2">

                <option>BCA</option>
                <option>BRI</option>
                <option>BNI</option>
                <option>MANDIRI</option>

            </select>

        </div>

        <!-- No Kartu -->

        <div class="mb-3">

            <label class="block text-sm mb-1">
                No Kartu
            </label>

            <input
                type="text"
                id="nomor-kartu"
                class="w-full border rounded-lg p-2">

        </div>

        <!-- Kembalian -->

        <div class="mb-4">

            <label class="block text-sm text-slate-500">
                Kembalian
            </label>

            <div
                id="kembalian"
                class="text-xl font-bold text-green-600">

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
                id="btn-simpan"
                onclick="saveTransaction()"
                class="flex-1 bg-green-600 text-white py-2 rounded-lg">
                Simpan
            </button>

        </div>

    </div>
</div>