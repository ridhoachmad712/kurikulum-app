@extends('layouts.app')
@section('title', 'Detail – ' . $mataKuliah->nama)
@section('content')

{{-- ── Header MK ─────────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
    <div>
        <a href="{{ route('master.mata-kuliah.index') }}"
           class="text-sm text-indigo-500 hover:text-indigo-700">← Kembali ke Daftar MK</a>
        <h1 class="text-xl font-bold text-gray-800 mt-1">{{ $mataKuliah->nama }}</h1>
    </div>
    <a href="{{ route('master.mata-kuliah.edit', $mataKuliah) }}"
       class="btn-primary text-sm">Edit MK</a>
</div>

{{-- Info card ----------------------------------------------------------------}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 text-sm">
    <div>
        <p class="text-xs text-gray-400 mb-0.5">Kode RPS</p>
        <p class="font-mono font-bold text-indigo-700 text-base">{{ $mataKuliah->kode_rps }}</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 mb-0.5">SKS</p>
        <p class="font-semibold text-gray-800">{{ $mataKuliah->sks }} SKS</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 mb-0.5">Semester</p>
        <p class="font-semibold text-gray-800">{{ $mataKuliah->semester ? 'Semester ' . $mataKuliah->semester : '–' }}</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 mb-0.5">Konsentrasi</p>
        <p class="font-semibold text-gray-800">{{ $mataKuliah->konsentrasi ?: '–' }}</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 mb-0.5">Status</p>
        <span class="px-2 py-0.5 rounded-full text-xs font-medium
            {{ $mataKuliah->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
            {{ $mataKuliah->aktif ? 'Aktif' : 'Non-aktif' }}
        </span>
    </div>
</div>

{{-- Dosen Pengampu -----------------------------------------------------------}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <h2 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Dosen Pengampu</h2>
    @if($mataKuliah->dosens->isEmpty())
        <p class="text-sm text-gray-400 italic">Belum ada dosen yang ditugaskan.</p>
    @else
        <div class="flex flex-wrap gap-2">
            @foreach($mataKuliah->dosens as $dosen)
            <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full border border-indigo-100">
                <svg class="w-3.5 h-3.5 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
                {{ $dosen->name }}
            </span>
            @endforeach
        </div>
    @endif
</div>

{{-- ── CPL Terkait ──────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">CPL yang Dibebankan</h2>
        <span class="text-xs text-gray-400">{{ $mataKuliah->mkCpls->count() }} CPL</span>
    </div>
    @if($mataKuliah->mkCpls->isEmpty())
        <p class="px-5 py-4 text-sm text-gray-400 italic">Belum ada keterkaitan CPL.
            <a href="{{ route('kurikulum.matriks.input') }}" class="text-indigo-500 hover:underline">Isi Matriks MK–CPL →</a>
        </p>
    @else
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2.5 text-left font-semibold text-gray-600 w-20">Kode</th>
                <th class="px-4 py-2.5 text-left font-semibold text-gray-600">Deskripsi CPL</th>
                <th class="px-4 py-2.5 text-center font-semibold text-gray-600 w-28">Level Keterkaitan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($mataKuliah->mkCpls->sortBy('cpl.urutan') as $mkCpl)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-bold text-indigo-700">{{ $mkCpl->cpl->kode }}</td>
                <td class="px-4 py-2.5 text-gray-700">{{ $mkCpl->cpl->deskripsi }}</td>
                <td class="px-4 py-2.5 text-center">
                    @php
                        $lvl = $mkCpl->level;
                        $cls = match($lvl) {
                            'Tinggi' => 'bg-green-100 text-green-700',
                            'Sedang' => 'bg-yellow-100 text-yellow-700',
                            'Rendah' => 'bg-red-100 text-red-700',
                            default  => 'bg-gray-100 text-gray-400',
                        };
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                        {{ $lvl ?? '–' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- ── CPMK & Bobot CPL ─────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">CPMK & Bobot CPL</h2>
        <span class="text-xs text-gray-400">{{ $mataKuliah->cpmks->count() }} CPMK</span>
    </div>

    @if($mataKuliah->cpmks->isEmpty())
        <p class="px-5 py-4 text-sm text-gray-400 italic">Belum ada CPMK.
            <a href="{{ route('kurikulum.cpmk.show', $mataKuliah) }}" class="text-indigo-500 hover:underline">Input CPMK →</a>
        </p>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($mataKuliah->cpmks as $cpmk)
        <div class="px-5 py-4">
            {{-- CPMK header --}}
            <div class="flex items-start gap-3 mb-3">
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-lg shrink-0 mt-0.5">
                    {{ $cpmk->kode }}
                </span>
                <p class="text-sm text-gray-800">{{ $cpmk->deskripsi }}</p>
            </div>

            {{-- CPL bobot tabel --}}
            @if($cpmk->cpmkCpls->isNotEmpty())
            <div class="ml-10 overflow-x-auto">
                <table class="text-xs border border-gray-100 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-1.5 text-left font-semibold text-gray-500 w-16">CPL</th>
                            <th class="px-3 py-1.5 text-left font-semibold text-gray-500">Deskripsi</th>
                            <th class="px-3 py-1.5 text-center font-semibold text-gray-500 w-20">Bobot %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($cpmk->cpmkCpls->sortBy('cpl.urutan') as $cc)
                        <tr>
                            <td class="px-3 py-1.5 font-bold text-indigo-600">{{ $cc->cpl->kode }}</td>
                            <td class="px-3 py-1.5 text-gray-600 max-w-md truncate" title="{{ $cc->cpl->deskripsi }}">
                                {{ Str::limit($cc->cpl->deskripsi, 80) }}
                            </td>
                            <td class="px-3 py-1.5 text-center font-semibold text-gray-700">
                                {{ number_format($cc->bobot_persen, 2) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="ml-10 text-xs text-gray-400 italic">Bobot CPL belum diisi.</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── Sub-CPMK & CLO ───────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700">Sub-CPMK & CLO</h2>
        <span class="text-xs text-gray-400">
            {{ $mataKuliah->cpmks->sum(fn($c) => $c->subCpmks->count()) }} Sub-CPMK
        </span>
    </div>

    @if($mataKuliah->cpmks->isEmpty() || $mataKuliah->cpmks->every(fn($c) => $c->subCpmks->isEmpty()))
        <p class="px-5 py-4 text-sm text-gray-400 italic">Belum ada Sub-CPMK.
            <a href="{{ route('kurikulum.sub-cpmk.index') }}" class="text-indigo-500 hover:underline">Input Sub-CPMK →</a>
        </p>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($mataKuliah->cpmks as $cpmk)
            @if($cpmk->subCpmks->isNotEmpty())
            <div class="px-5 py-4">
                {{-- CPMK label --}}
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wide mb-3">
                    {{ $cpmk->kode }} — {{ Str::limit($cpmk->deskripsi, 70) }}
                </p>

                {{-- Sub-CPMK table --}}
                <table class="min-w-full text-xs border border-gray-100 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-1.5 text-left font-semibold text-gray-500 w-28">Kode</th>
                            <th class="px-3 py-1.5 text-left font-semibold text-gray-500">Deskripsi Sub-CPMK</th>
                            <th class="px-3 py-1.5 text-center font-semibold text-gray-500 w-24">CLO Tertinggi</th>
                            <th class="px-3 py-1.5 text-left font-semibold text-gray-500 w-40">Semua CLO</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @foreach($cpmk->subCpmks as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-semibold text-gray-700">{{ $sub->kode }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $sub->deskripsi }}</td>
                            <td class="px-3 py-2 text-center">
                                @if($sub->kode_clo_tertinggi)
                                <span class="bg-purple-100 text-purple-700 font-bold px-2 py-0.5 rounded-full">
                                    {{ $sub->kode_clo_tertinggi }}
                                </span>
                                @else
                                <span class="text-gray-300">–</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($sub->subCpmkClos->sortBy('cloTaxonomi.kode') as $sc)
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium
                                        {{ $sc->is_tertinggi ? 'bg-purple-100 text-purple-700 ring-1 ring-purple-300' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $sc->cloTaxonomi->kode }}
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        @endforeach
    </div>
    @endif
</div>

{{-- Quick-nav ke halaman terkait -------------------------------------------}}
<div class="flex flex-wrap gap-3 text-sm">
    <a href="{{ route('kurikulum.cpmk.show', $mataKuliah) }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition border border-indigo-100">
        ✏️ Edit CPMK & Bobot
    </a>
    <a href="{{ route('kurikulum.sub-cpmk.index') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition border border-indigo-100">
        ✏️ Edit Sub-CPMK
    </a>
    <a href="{{ route('kurikulum.asesmen.show', $mataKuliah) }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition border border-indigo-100">
        📋 Asesmen
    </a>
    <a href="{{ route('kurikulum.matriks.input') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition border border-indigo-100">
        📊 Matriks MK–CPL
    </a>
</div>

@endsection
