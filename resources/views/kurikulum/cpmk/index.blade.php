@extends('layouts.app')
@section('title','CPMK – Pilih Mata Kuliah')
@section('content')

<x-page-header title="CPMK" subtitle="Capaian Pembelajaran Mata Kuliah — input dan keterkaitan CPL"/>

<div class="flex items-center justify-between mb-4 flex-wrap gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('kurikulum.matriks.input') }}"
           class="text-xs text-indigo-500 hover:text-indigo-700 hover:underline transition">
            ← Lihat Tabel 1–12
        </a>
    </div>
    <x-search-input placeholder="Cari kode atau nama MK..." />
</div>

{{-- Filter semester --}}
<div class="inline-flex items-center bg-gray-100 rounded-xl p-1 gap-0.5 mb-5 flex-wrap">
    <a href="{{ route('kurikulum.cpmk.index') }}"
       class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors
              {{ !request('semester') ? 'bg-white text-indigo-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        Semua
    </a>
    @for($s=1;$s<=8;$s++)
    <a href="{{ route('kurikulum.cpmk.index', ['semester'=>$s]) }}"
       class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors
              {{ request('semester')==$s ? 'bg-white text-indigo-700 font-semibold shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
        Smt {{ $s }}
    </a>
    @endfor
</div>
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
                <td class="px-4 py-2.5 font-mono text-indigo-600 text-xs">{{ $mk->kode_rps }}</td>
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
                       class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700">
                        {{ $mk->cpmks_count > 0 ? 'Edit CPMK' : 'Isi CPMK' }}
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-0">
                <x-empty-state icon="document"
                    :title="request('search') ? 'Tidak ada hasil pencarian' : 'Belum ada mata kuliah'"
                    :subtitle="request('search') ? 'Coba kata kunci lain.' : 'Tambahkan mata kuliah di menu Master terlebih dahulu.'"/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $mataKuliahs->withQueryString()->links() }}</div>
</div>
@endsection
