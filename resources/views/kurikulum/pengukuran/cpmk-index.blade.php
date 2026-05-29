@extends('layouts.app')
@section('title','Pengukuran CPMK')
@section('content')

<x-page-header title="Pengukuran CPMK" subtitle="Rekap nilai capaian CPMK per mata kuliah berdasarkan hasil asesmen mahasiswa"/>

{{-- Info scope dosen --}}
@hasrole('dosen')
<div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda. Hubungi Kaprodi jika ada MK yang tidak muncul.</span>
</div>
@endhasrole

<div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 text-xs text-blue-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
    </svg>
    <span>
        <strong>Pengukuran CPMK</strong> — Distribusi bobot instrumen asesmen (Tugas, Kuis, Kasus/Proyek, UTS, UAS) per Sub-CPMK.
        Total bobot per Sub-CPMK harus sesuai dengan bobot teoritis Sub-CPMK tersebut (bukan 100%).
    </span>
</div>

@forelse($mataKuliahs as $mk)
@php
    $pct        = $mk->total_sub > 0 ? round($mk->selaras_sub / $mk->total_sub * 100) : 0;
    $allSelaras = $mk->selaras_sub === $mk->total_sub && $mk->total_sub > 0;
    $noTarget   = $mk->total_sub === 0;   // semua CPMK belum punya bobot CPL
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-3 hover:border-gray-200 transition-colors">
    <div class="px-5 py-4 flex items-center gap-4">
        {{-- Kode + nama --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="font-mono text-xs text-gray-400">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-gray-800 text-sm">{{ $mk->nama }}</span>
                <span class="text-xs text-gray-400">· Smt {{ $mk->semester }} · {{ $mk->sks }} SKS</span>
            </div>
            {{-- Progress bar --}}
            <div class="mt-2.5 flex items-center gap-3">
                @if($noTarget)
                <span class="text-xs text-amber-500 font-medium">⚠ Bobot CPMK belum diisi — target selaras belum dapat dihitung</span>
                @else
                <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500
                                {{ $allSelaras ? 'bg-green-500' : ($pct > 0 ? 'bg-amber-400' : 'bg-gray-200') }}"
                         style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-xs font-semibold shrink-0
                             {{ $allSelaras ? 'text-green-600' : ($pct > 0 ? 'text-amber-600' : 'text-gray-400') }}">
                    {{ $mk->selaras_sub }}/{{ $mk->total_sub }} Sub-CPMK selaras
                </span>
                @endif
            </div>
        </div>

        {{-- Badge status --}}
        @if($allSelaras)
        <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Selaras
        </span>
        @elseif($mk->filled_sub > 0)
        <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
            Sebagian
        </span>
        @else
        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400">
            Belum diisi
        </span>
        @endif

        <a href="{{ route('kurikulum.pengukuran.cpmk.show', $mk) }}"
           class="shrink-0 btn-primary btn-sm flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Input
        </a>
    </div>
</div>
@empty
<div class="bg-white rounded-xl shadow-sm border border-gray-100 py-14 text-center">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
    </svg>
    @if(auth()->user()->hasRole('dosen'))
        <p class="font-medium text-gray-500">Belum ada mata kuliah yang ditugaskan</p>
        <p class="text-sm text-gray-400 mt-1">Hubungi Kaprodi untuk mendapatkan penugasan mata kuliah.</p>
    @else
        <p class="font-medium text-gray-500">Belum ada Mata Kuliah dengan Sub-CPMK</p>
        <p class="text-sm text-gray-400 mt-1">Pastikan CPMK dan Sub-CPMK sudah diinput terlebih dahulu.</p>
    @endif
</div>
@endforelse

@endsection

@section('help')
<div class="space-y-4">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Apa itu Pengukuran CPMK?</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Pengukuran CPMK adalah proses mendistribusikan bobot instrumen asesmen (Tugas, Kuis, Kasus/Proyek, UTS, UAS) ke setiap Sub-CPMK. Total bobot per Sub-CPMK harus sesuai dengan bobot teoritis Sub-CPMK tersebut.</p>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Cara Mengisi</h3>
        <ol class="list-decimal list-inside space-y-1 text-xs text-gray-500">
            <li>Pilih mata kuliah yang ingin diisi pengukurannya.</li>
            <li>Klik <strong>Input</strong> untuk membuka halaman detail.</li>
            <li>Distribusikan bobot setiap instrumen asesmen ke masing-masing Sub-CPMK.</li>
            <li>Pastikan total bobot per Sub-CPMK selaras dengan bobot yang telah ditetapkan.</li>
        </ol>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Indikator Status</h3>
        <ul class="space-y-1.5 text-xs text-gray-500">
            <li class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Selaras</span>
                <span>Semua Sub-CPMK sudah terisi dan bobotnya sesuai.</span>
            </li>
            <li class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Sebagian</span>
                <span>Sebagian Sub-CPMK sudah terisi, sebagian belum.</span>
            </li>
            <li class="flex items-center gap-2">
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-400">Belum diisi</span>
                <span>Belum ada instrumen asesmen yang diisi.</span>
            </li>
        </ul>
    </div>
    <div class="bg-amber-50 rounded-xl px-4 py-3">
        <p class="text-xs text-amber-700 font-medium">⚠ Prasyarat</p>
        <p class="text-xs text-amber-600 mt-1 leading-relaxed">Halaman ini memerlukan Sub-CPMK dan bobot CPMK yang sudah diisi terlebih dahulu. MK yang belum memiliki Sub-CPMK tidak akan tampil di daftar ini.</p>
    </div>
</div>
@endsection
