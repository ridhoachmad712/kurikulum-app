@extends('layouts.app')
@section('title','Sub-CPMK – Pilih CPMK')
@section('content')

<x-page-header title="Sub-CPMK" subtitle="Kelola sub-capaian dan CLO tertinggi per CPMK"/>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('kurikulum.sub-cpmk.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('semester') ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Semua
        </a>
        @for($s=1;$s<=8;$s++)
        <a href="{{ route('kurikulum.sub-cpmk.index', ['semester'=>$s]) }}"
           class="px-3 py-2 text-sm font-medium rounded-lg {{ request('semester')==$s ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Smt {{ $s }}
        </a>
        @endfor
    </div>
    <div class="flex gap-2 items-center flex-wrap">
        <x-search-input placeholder="Cari kode atau nama MK..." />
        <a href="{{ route('kurikulum.sub-cpmk.tabel8') }}" class="btn-secondary btn-sm">Tabel 8</a>
    </div>
</div>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong></p>
@endif

@forelse($mataKuliahs as $mk)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <div>
            <span class="font-mono text-xs text-gray-700 mr-2">{{ $mk->kode_rps }}</span>
            <span class="font-semibold text-gray-800">{{ $mk->nama }}</span>
            <span class="ml-2 text-xs text-gray-400">{{ $mk->sks }} SKS · Smt {{ $mk->semester }}</span>
        </div>
    </div>
    <table class="min-w-full divide-y divide-gray-50 text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-4 py-2 text-left w-28">CPMK</th>
                <th class="px-4 py-2 text-left">Deskripsi</th>
                <th class="px-4 py-2 text-center w-28">Sub-CPMK</th>
                <th class="px-4 py-2 text-center w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($mk->cpmks as $cpmk)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-semibold text-gray-700 text-xs">{{ $cpmk->kode }}</td>
                <td class="px-4 py-2.5 text-gray-700">{{ $cpmk->deskripsi }}</td>
                <td class="px-4 py-2.5 text-center">
                    @if($cpmk->sub_cpmks_count > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $cpmk->sub_cpmks_count }} Sub-CPMK
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            Belum ada
                        </span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('kurikulum.sub-cpmk.show', $cpmk) }}"
                       class="text-xs px-3 py-1 bg-gray-700 text-white rounded-lg hover:bg-gray-700">
                        Kelola
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@empty
<x-empty-state icon="document" title="Belum ada CPMK"
    subtitle="Isi CPMK terlebih dahulu sebelum menambahkan Sub-CPMK."
    action="{{ route('kurikulum.cpmk.index') }}"
    actionLabel="Isi CPMK"/>
@endforelse

@endsection
