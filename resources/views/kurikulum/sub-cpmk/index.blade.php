@extends('layouts.app')
@section('title','Sub-CPMK')
@section('content')

<x-page-header title="Sub-CPMK" subtitle="Kelola sub-capaian pembelajaran, keterkaitan CPMK, dan CLO tertinggi"/>

<x-dosen-stepper :step="3" />

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div class="flex gap-2 items-center flex-wrap">
        <x-search-input placeholder="Cari kode atau nama MK..." />
    </div>
</div>

@hasrole('dosen')
<div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda.</span>
</div>
@endhasrole

@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong></p>
@endif

@forelse($mataKuliahs as $mk)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
    <div class="px-5 py-3 bg-gray-50 flex items-center justify-between gap-3">
        <div>
            <span class="font-mono text-xs text-gray-500 mr-2">{{ $mk->kode_rps }}</span>
            <span class="font-semibold text-gray-800">{{ $mk->nama }}</span>
            <span class="ml-2 text-xs text-gray-400">{{ $mk->sks }} SKS · Smt {{ $mk->semester }}</span>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if($mk->sub_cpmks_count > 0)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                {{ $mk->sub_cpmks_count }} Sub-CPMK
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                Belum ada
            </span>
            @endif
            <a href="{{ route('kurikulum.sub-cpmk.show', $mk) }}"
               class="btn-primary btn-sm">
                Kelola →
            </a>
        </div>
    </div>

    {{-- Info ringkas CPMK dan koneksinya --}}
    @if($mk->cpmks->isNotEmpty())
    <div class="px-5 py-3 flex flex-wrap gap-1.5 border-t border-gray-50">
        @foreach($mk->cpmks as $cpmk)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                     {{ $cpmk->sub_cpmks_count > 0 ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
            {{ $cpmk->kode }}
            @if($cpmk->sub_cpmks_count > 0)
            <span class="font-semibold">{{ $cpmk->sub_cpmks_count }}</span>
            @endif
        </span>
        @endforeach
    </div>
    @endif
</div>
@empty
@if(auth()->user()->hasRole('dosen'))
    <x-empty-state icon="document"
        title="Belum ada mata kuliah yang ditugaskan"
        subtitle="Anda belum mendapat penugasan mata kuliah. Hubungi Kaprodi untuk pengaturan penugasan."/>
@else
    <x-empty-state icon="document" title="Belum ada CPMK"
        subtitle="Isi CPMK terlebih dahulu sebelum menambahkan Sub-CPMK."
        action="{{ route('kurikulum.cpmk.index') }}"
        actionLabel="Isi CPMK"/>
@endif
@endforelse

@endsection
