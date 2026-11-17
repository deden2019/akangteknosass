@extends('layouts.app')

@section('title', 'Pengaturan Metode Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Metode Pembayaran (Kartu)</h1>
            <p class="text-xs text-slate-500">Daftar metode pembayaran dari tabel `m_kartu`.</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Tombol Tambah -->
            <button type="button" onclick="openModalTambah()" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl transition shadow-sm flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Metode
            </button>
            <a href="{{ route('setting.index') }}" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-3 py-2 rounded-xl transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Tabel Data Kartu -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Metode / Kartu</th>
                        <th class="p-3 text-center">Jenis (Debit/Kredit)</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($listOfKartu as $index => $kartu)
                        <tr>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold">{{ $kartu->nama_kartu }}</td>
                            <td class="p-3 text-center">
                                @if($kartu->is_debit)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold">Debit / QRIS</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-bold">Kredit / Lainnya</span>
                                @endif
                            </td>
                            <td class="p-3 text-center space-x-1">
                                <!-- Tombol Edit -->
                                <button type="button" onclick="openModalEdit('{{ $kartu->kartu_id }}', '{{ $kartu->nama_kartu }}', '{{ $kartu->is_debit ? 1 : 0 }}')" 
                                    class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg transition" title="Edit">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                </button>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('setting.kartu.destroy', $kartu->kartu_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?')">
                                    @csrf
                                    <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 italic">Data metode pembayaran belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FORM DINAMIS (TAMBAH / EDIT) -->
<div id="modalKartu" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 id="modalTitle" class="text-sm font-bold text-slate-800">Tambah Metode Pembayaran</h3>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="formKartu" method="POST" action="{{ route('setting.kartu.store') }}" class="space-y-4">
    @csrf
    <div id="methodField"></div> 

    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Metode / Kartu</label>
        <input type="text" id="nama_kartu" name="nama_kartu" required placeholder="Contoh: QRIS, Debit BNI, dll" 
            class="w-full text-xs border border-slate-200 rounded-xl p-2.5 outline-none focus:border-emerald-500">
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Pembayaran</label>
        <div class="flex gap-4 mt-2">
            <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                <input type="radio" name="is_debit" value="1" id="rdoDebit" checked class="accent-emerald-600"> Debit / QRIS / Cash
            </label>
            <label class="flex items-center gap-2 text-xs font-medium text-slate-700 cursor-pointer">
                <input type="radio" name="is_debit" value="0" id="rdoKredit" class="accent-emerald-600"> Kredit / Lainnya
            </label>
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
            Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
            Simpan Data
        </button>
    </div>
</form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('modalKartu');
    const form = document.getElementById('formKartu');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const inputNama = document.getElementById('nama_kartu');
    const rdoDebit = document.getElementById('rdoDebit');
    const rdoKredit = document.getElementById('rdoKredit');

    function openModalTambah() {
        modalTitle.innerText = "Tambah Metode Pembayaran";
        form.action = "{{ route('setting.kartu.store') }}";
        methodField.innerHTML = ""; // Pastikan tidak ada _method PUT
        inputNama.value = "";
        rdoDebit.checked = true;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        inputNama.focus();
    }

    function openModalEdit(id, nama, isDebit) {
        modalTitle.innerText = "Edit Metode Pembayaran";
        form.action = "{{ url('/setting/kartu/update') }}/" + id;
        methodField.innerHTML = '@method("PUT")'; // Spoofing method PUT untuk update
        inputNama.value = nama;
        if (isDebit == '1') {
            rdoDebit.checked = true;
        } else {
            rdoKredit.checked = true;
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        inputNama.focus();
    }

    function closeModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection