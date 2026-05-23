@extends('layouts.public')
@section('title', $mataKuliah->nama)

@section('content')

{{-- Page header --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-3 flex-wrap">
            <a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('public.mata-kuliah') }}" class="hover:text-indigo-600 transition">Mata Kuliah</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-600 font-medium">{{ $mataKuliah->kode_rps }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-sm text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-lg">{{ $mataKuliah->kode_rps }}</span>
                    <span class="text-xs text-gray-400">{{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $mataKuliah->nama }}</h1>
                @if($mataKuliah->konsentrasi)
                <p class="text-sm text-gray-500 mt-1">Konsentrasi: {{ $mataKuliah->konsentrasi }}</p>
                @endif
            </div>
            {{-- CPL badges --}}
            <div class="flex flex-wrap gap-1.5 sm:justify-end">
                @foreach($cpls->whereIn('id', $relatedCplIds) as $cpl)
                <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">{{ $cpl->kode }}</span>
                @endforeach
            </div>
        </div>

        @if($mataKuliah->dosens->isNotEmpty())
        <div class="mt-3 flex items-center gap-2 flex-wrap">
            <span class="text-xs text-gray-400">Dosen:</span>
            @foreach($mataKuliah->dosens as $d)
            <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded-full font-medium">{{ $d->name }}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

{{-- ─── CPMK LIST ───────────────────────────────────────────── --}}
<section>
    <h2 class="text-lg font-bold text-gray-900 mb-4">
        Capaian Pembelajaran Mata Kuliah (CPMK)
        <span class="ml-2 text-sm font-normal text-gray-400">({{ $mataKuliah->cpmks->count() }} CPMK)</span>
    </h2>

    @if($mataKuliah->cpmks->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-sm text-amber-700 text-center">
        Belum ada CPMK yang ditetapkan untuk mata kuliah ini.
    </div>
    @else
    <div class="space-y-4">
        @foreach($mataKuliah->cpmks as $cpmk)
        @php
            $cpmkCpls   = $cpmkCplMap[$cpmk->id] ?? [];
            $hasRelCpls = collect($cpmkCpls)->where('has_relation', true)->keys();
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- CPMK header --}}
            <div class="px-5 py-4 border-b border-gray-50 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="shrink-0 w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                        {{ $cpmk->urutan }}
                    </span>
                    <div>
                        <p class="font-semibold text-indigo-700 text-sm">{{ $cpmk->kode }}</p>
                        <p class="text-gray-700 text-sm mt-0.5">{{ $cpmk->deskripsi }}</p>
                    </div>
                </div>
                {{-- CPL badges for this CPMK --}}
                <div class="shrink-0 flex flex-wrap gap-1 justify-end">
                    @foreach($cpls->whereIn('id', $hasRelCpls->toArray()) as $cpl)
                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-full">{{ $cpl->kode }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Bobot per CPL --}}
            @if($cpmkCpls)
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-50">
                <p class="text-xs text-gray-400 font-medium mb-2">Bobot kontribusi terhadap CPL:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($cpls->whereIn('id', array_keys($cpmkCpls)) as $cpl)
                    @php $entry = $cpmkCpls[$cpl->id] ?? []; @endphp
                    @if(($entry['has_relation'] ?? false) && ($entry['bobot'] ?? 0) > 0)
                    <div class="flex items-center gap-1.5 bg-white border border-indigo-100 rounded-lg px-2.5 py-1">
                        <span class="text-xs font-semibold text-indigo-600">{{ $cpl->kode }}</span>
                        <span class="text-xs text-gray-500">{{ number_format($entry['bobot'], 1) }}%</span>
                    </div>
                    @endif
                    @endforeach
                    @if(collect($cpmkCpls)->filter(fn($e) => ($e['has_relation'] ?? false) && ($e['bobot'] ?? 0) > 0)->isEmpty())
                    <span class="text-xs text-gray-300 italic">Bobot belum diisi</span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Sub-CPMK --}}
            @if($cpmk->subCpmks->isNotEmpty())
            <div class="divide-y divide-gray-50">
                @foreach($cpmk->subCpmks as $sc)
                <div class="px-5 py-3 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <span class="shrink-0 text-xs font-mono font-semibold text-teal-600 pt-0.5">{{ $sc->kode }}</span>
                        <p class="text-sm text-gray-700">{{ $sc->deskripsi }}</p>
                    </div>
                    {{-- Asesmen bobot --}}
                    @if($sc->asesmen)
                    @php $a = $sc->asesmen; @endphp
                    <div class="shrink-0 flex flex-wrap gap-1.5 text-xs justify-end">
                        @foreach(['tugas'=>'Tugas','quis'=>'Quis','kasus_proyek'=>'Kasus','uts'=>'UTS','uas'=>'UAS'] as $field => $label)
                        @if($a->$field > 0)
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full font-medium">{{ $label }}: {{ $a->$field }}%</span>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-4 text-sm text-gray-400 italic">Belum ada Sub-CPMK.</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ─── KETERKAITAN CPL ─────────────────────────────────────── --}}
@if($mataKuliah->cpmks->isNotEmpty() && $cpls->isNotEmpty())
<section>
    <h2 class="text-lg font-bold text-gray-900 mb-4">Matriks CPMK × CPL</h2>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-indigo-900 text-white">
                <tr>
                    <th class="sticky left-0 bg-indigo-900 px-4 py-3 text-left min-w-[220px]">CPMK</th>
                    @foreach($cpls->whereIn('id', $relatedCplIds) as $cpl)
                    <th class="px-3 py-3 text-center w-20">{{ $cpl->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($mataKuliah->cpmks as $cpmk)
                <tr class="hover:bg-indigo-50/30">
                    <td class="sticky left-0 bg-white px-4 py-2.5 border-r border-gray-100">
                        <span class="font-semibold text-indigo-700">{{ $cpmk->kode }}</span>
                        <p class="text-gray-400 text-xs mt-0.5 line-clamp-1">{{ $cpmk->deskripsi }}</p>
                    </td>
                    @foreach($cpls->whereIn('id', $relatedCplIds) as $cpl)
                    @php $entry = $cpmkCplMap[$cpmk->id][$cpl->id] ?? []; @endphp
                    <td class="px-3 py-2.5 text-center">
                        @if($entry['has_relation'] ?? false)
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 font-bold">✓</span>
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

</div>

{{-- Back link --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <a href="{{ route('public.mata-kuliah') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke daftar mata kuliah
    </a>
</div>

@endsection
