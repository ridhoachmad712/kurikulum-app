@extends('layouts.app')
@section('title','Dashboard – Tugas Saya')
@section('content')

{{-- ── STAT CARDS ── --}}
<div class="grid grid-cols-3 gap-4 mb-7">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">MK Ditugaskan</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $summary['total_mk'] }}</p>
        <p class="text-xs text-gray-400 mt-1">mata kuliah aktif</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Sudah Lengkap</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $summary['mk_lengkap'] }}</p>
        @php $pct = $summary['total_mk'] > 0 ? round($summary['mk_lengkap'] / $summary['total_mk'] * 100) : 0; @endphp
        <div class="mt-2 bg-gray-100 rounded-full h-1.5">
            <div class="bg-green-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-0.5">{{ $pct }}% selesai</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Perlu Dilengkapi</p>
        <p class="text-3xl font-bold text-amber-500 mt-1">{{ $summary['mk_pending'] }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $summary['mk_pending'] > 0 ? 'segera selesaikan' : 'semua sudah lengkap 🎉' }}
        </p>
    </div>
</div>

{{-- ── MK CARDS ── --}}
@if($mataKuliahs->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <p class="font-semibold text-gray-500">Belum ada penugasan</p>
    <p class="text-sm text-gray-400 mt-1">Hubungi Kaprodi untuk mendapatkan mata kuliah yang perlu diisi.</p>
</div>
@else

<div class="flex items-center justify-between mb-4">
    <h2 class="text-sm font-semibold text-gray-600">Mata Kuliah Saya ({{ $mataKuliahs->count() }} MK)</h2>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
@foreach($mataKuliahs as $mk)
@php
    $allClo  = $mk->sub_cpmks_count > 0 && $mk->sub_clo_count  == $mk->sub_cpmks_count;
    $allAses = $mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count;

    $checks = [
        ['label' => 'Matriks MK–CPL',  'done' => $mk->has_matriks,        'href' => route('kurikulum.matriks.input')],
        ['label' => 'CPMK',             'done' => $mk->cpmks_count > 0,    'href' => route('kurikulum.cpmk.show', $mk)],
        ['label' => 'Sub-CPMK',         'done' => $mk->sub_cpmks_count > 0,'href' => route('kurikulum.sub-cpmk.index')],
        ['label' => 'CLO Tertinggi',    'done' => $allClo,                 'href' => route('kurikulum.sub-cpmk.index')],
        ['label' => 'Bobot Asesmen',    'done' => $allAses,                'href' => route('kurikulum.asesmen.show', $mk)],
    ];

    // First incomplete step
    $nextHref  = null;
    $nextLabel = null;
    foreach ($checks as $step) {
        if (!$step['done']) {
            $nextHref  = $step['href'];
            $nextLabel = $step['label'];
            break;
        }
    }

    $barPct   = round($mk->score / 5 * 100);
    $barColor = $mk->score == 5 ? 'bg-green-500' : ($mk->score >= 3 ? 'bg-indigo-500' : 'bg-amber-400');
    $badgeClass = match($mk->score) {
        5       => 'bg-green-100 text-green-700',
        0       => 'bg-gray-100 text-gray-400',
        default => 'bg-amber-100 text-amber-700',
    };
    $badgeLabel = match($mk->score) {
        5 => 'Lengkap',
        0 => 'Belum mulai',
        default => "Langkah {$mk->score}/5",
    };
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden
            {{ $mk->score == 5 ? 'border-l-4 border-l-green-400' : ($mk->score == 0 ? '' : 'border-l-4 border-l-amber-400') }}">

    {{-- Card header --}}
    <div class="px-5 pt-4 pb-3">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="font-mono text-xs text-indigo-600 font-semibold">{{ $mk->kode_rps }}</span>
                    <span class="text-xs text-gray-400">Smt {{ $mk->semester }}</span>
                    @if($mk->sks)
                    <span class="text-xs text-gray-400">· {{ $mk->sks }} SKS</span>
                    @endif
                </div>
                <p class="font-semibold text-gray-800 text-sm leading-snug">{{ $mk->nama }}</p>
            </div>
            <span class="shrink-0 inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                {{ $badgeLabel }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="flex items-center gap-2.5">
            <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500"
                     style="width: {{ $barPct }}%"></div>
            </div>
            <span class="text-xs font-semibold text-gray-500 shrink-0">{{ $mk->score }}/5</span>
        </div>
    </div>

    {{-- Checklist --}}
    <div class="px-5 py-3 border-t border-gray-50 grid grid-cols-2 gap-x-4 gap-y-2">
        @foreach($checks as $step)
        <div class="flex items-center gap-2">
            @if($step['done'])
                {{-- Done: green checkmark --}}
                <span class="shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span class="text-xs text-gray-500 line-through">{{ $step['label'] }}</span>
            @else
                {{-- Pending: link to fill --}}
                <span class="shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
                <a href="{{ $step['href'] }}"
                   class="text-xs text-indigo-600 hover:underline font-medium">{{ $step['label'] }}</a>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Card footer: quick action --}}
    <div class="px-5 py-3 mt-auto border-t border-gray-50 flex items-center justify-between gap-3">
        @if($mk->score == 5)
            <span class="text-xs text-green-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Semua langkah selesai
            </span>
            <a href="{{ route('kurikulum.asesmen.show', $mk) }}"
               class="text-xs text-gray-400 hover:text-indigo-600 hover:underline">Lihat asesmen →</a>
        @else
            <p class="text-xs text-gray-400">
                Lanjutkan:
                <span class="font-medium text-gray-600">{{ $nextLabel }}</span>
            </p>
            <a href="{{ $nextHref }}"
               class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700
                      text-white text-xs font-semibold rounded-lg transition-colors">
                Isi sekarang
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

</div>
@endforeach
</div>

@endif

@endsection
