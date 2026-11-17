@extends('layouts.app')

@section('title', 'Pengaturan Shift Operasional')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex justify-between items-center">
        <div>
            <h1 class="text-lg font-bold text-slate-800">Pengaturan Shift Operasional</h1>
            <p class="text-xs text-slate-500">Daftar shift kerja aktif dari tabel `m_shift`.</p>
        </div>
        <a href="{{ route('setting.index') }}" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-3 py-2 rounded-xl transition">
            Kembali
        </a>
    </div>

    <!-- Tabel Data Shift -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Shift</th>
                        <th class="p-3">Jam Mulai</th>
                        <th class="p-3">Jam Selesai</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($listOfShift as $index => $shift)
                        <tr>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold">{{ $shift->nama_shift }}</td>
                            <td class="p-3">{{ $shift->jam_mulai }}</td>
                            <td class="p-3">{{ $shift->jam_selesai }}</td>
                            <td class="p-3 text-center">
                                @if($shift->is_active)
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 italic">Data shift belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection