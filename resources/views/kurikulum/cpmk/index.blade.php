@extends('layouts.app')
@section('title','CPMK – Pilih Mata Kuliah')
@section('content')

<x-page-header title="CPMK" subtitle="Capaian Pembelajaran Mata Kuliah — input dan keterkaitan CPL"/>

<x-dosen-stepper :step="2" />

<div class="flex items-center justify-between mb-4 flex-wrap gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('kurikulum.matriks.input') }}"
           class="text-xs text-gray-400 hover:text-gray-800 hover:underline transition">
            ← Lihat Tabel 1–12
        </a>
    </div>
    <x-search-input placeholder="Cari kode atau nama MK..." />
</div>

{{-- Info scope dosen --}}
@hasrole('dosen')
<div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda. Hubungi Kaprodi jika ada MK yang tidak muncul.</span>
</div>
@endhasrole

@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong> — {{ $mataKuliahs->total() }} ditemukan</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode RPS</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-12">SKS</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-12">Smt</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">CPMK</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($mataKuliahs as $mk)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-mono text-gray-700 text-xs">{{ $mk->kode_rps }}</td>
                <td class="px-4 py-2.5 font-medium text-gray-800">{{ $mk->nama }}</td>
                <td class="px-4 py-2.5 text-center text-gray-500">{{ $mk->sks }}</td>
                <td class="px-4 py-2.5 text-center text-gray-500">{{ $mk->semester }}</td>
                <td class="px-4 py-2.5 text-center">
                    @if($mk->cpmks_count > 0)
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            {{ $mk->cpmks_count }} CPMK
                        </span>
                    @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded-full text-xs">Belum diisi</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('kurikulum.cpmk.show', $mk) }}"
                       class="btn-primary btn-sm">
                        {{ $mk->cpmks_count > 0 ? 'Edit CPMK' : 'Isi CPMK' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-0">
                @if(request('search'))
                    <x-empty-state icon="document" title="Tidak ada hasil pencarian" subtitle="Coba kata kunci lain."/>
                @elseif(auth()->user()->hasRole('dosen'))
                    <x-empty-state icon="document"
                        title="Belum ada mata kuliah yang ditugaskan"
                        subtitle="Anda belum mendapat penugasan mata kuliah. Hubungi Kaprodi untuk pengaturan penugasan."/>
                @else
                    <x-empty-state icon="document" title="Belum ada mata kuliah"
                        subtitle="Tambahkan mata kuliah di menu Master terlebih dahulu."/>
                @endif
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $mataKuliahs->withQueryString()->links() }}</div>
</div>
@endsection
