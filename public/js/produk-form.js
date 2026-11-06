document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formTambahProduk");
    const inputHargaBeli = document.querySelector('input[name="harga_beli"]');
    const inputHargaJual = document.querySelector('input[name="harga_jual"]');

    // Validasi sederhana: Harga jual tidak boleh lebih kecil dari harga beli
    if (form) {
        form.addEventListener("submit", function (e) {
            const hargaBeli = parseFloat(inputHargaBeli.value) || 0;
            const hargaJual = parseFloat(inputHargaJual.value) || 0;

            if (hargaJual < hargaBeli) {
                const konfirmasi = confirm(
                    "Peringatan: Harga jual lebih kecil dari harga beli. Apakah Anda yakin ingin melanjutkan?",
                );
                if (!konfirmasi) {
                    e.preventDefault();
                }
            }
        });
    }
});
