@extends('layouts.app')

@section('title', 'Edit Produk - POS')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .form-label {
            font-weight: 600;
            color: #03ac0e;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dce7f5;
            padding: 0.6rem 0.9rem;
            color: #25396f;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #03ac0e;
            box-shadow: 0 0 0 0.25rem rgba(3, 172, 14, 0.15);
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #dce7f5;
            color: #607080;
            border-radius: 8px 0 0 8px;
        }
    </style>
@endpush

@section('content')

    <!-- Header Halaman -->
    <div class="mb-4">
        <h3 class="font-bold text-lg text-slate-800">
            <i class="bi bi-pencil-square me-2 text-emerald-600"></i>Edit Produk
        </h3>
        <p class="text-xs text-slate-500 mb-0">Perbarui informasi data produk di dalam sistem.</p>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <form id="formEditProduk" method="POST" action="/produk/update/{{ $produk->produk_id }}">
                        @csrf
                        @method('PUT')
                        <!-- Kode Produk & Nama Produk -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kode Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-barcode"></i></span>
                                    <input type="text" name="kode_produk" class="form-control"
                                        value="{{ old('kode_produk', $produk->kode_produk) }}" required autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Nama Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="nama_produk" class="form-control"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}" required autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <!-- Harga Beli & Harga Jual -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_beli" class="form-control"
                                        value="{{ old('harga_beli', $produk->harga_beli ? intval($produk->harga_beli) : '') }}"
                                        min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_jual" class="form-control"
                                        value="{{ old('harga_jual', $produk->harga_jual ? intval($produk->harga_jual) : '') }}"
                                        min="0" required>
                                </div>
                            </div>
                        </div>

                        <!-- Stok Gudang -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Stok Gudang</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                    <input type="number" name="stok_gudang" class="form-control"
                                        value="{{ old('stok_gudang', $produk->stok_gudang ? intval($produk->stok_gudang) : '') }}"
                                        min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Satuan</label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-box"></i>
                                </span>

                                <input type="text" name="satuan" class="form-control"
                                    value="{{ old('satuan', $produk->satuan) }}" required>
                            </div>
                        </div>

                        <hr class="my-3" style="border-color: #ebf3fe;">

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/produk" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success"
                                style="background-color: #03ac0e; border-color: #03ac0e;">
                                <i class="bi bi-save me-1"></i> Perbarui Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('formEditProduk').addEventListener('submit', function(e) {
            const hargaBeli = parseFloat(document.querySelector('input[name="harga_beli"]').value) || 0;
            const hargaJual = parseFloat(document.querySelector('input[name="harga_jual"]').value) || 0;

            if (hargaJual < hargaBeli) {
                if (!confirm('Peringatan: Harga jual lebih kecil dari harga beli. Yakin ingin melanjutkan?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
@endpush
