@extends('layouts.public')
@section('title', 'Beranda')

@section('content')

{{-- ─── PRODI INFO BAR ──────────────────────────────────────── --}}
<div class="bg-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold leading-tight">{{ $prodi->nama }}</h1>
                <p class="text-indigo-200 text-sm mt-0.5">{{ $prodi->fakultas }} · {{ $prodi->universitas }}</p>
            </div>
            <div class="flex items-center gap-6 shrink-0">
                @foreach([
                    ['val' => $stats['total_mk'],   'label' => 'Mata Kuliah'],
                    ['val' => $stats['total_cpl'],  'label' => 'CPL'],
                    ['val' => $stats['total_sks'],  'label' => 'SKS'],
                    ['val' => $stats['total_cpmk'], 'label' => 'CPMK'],
                ] as $s)
                <div class="text-center">
                    <p class="text-2xl font-extrabold">{{ $s['val'] }}</p>
                    <p class="text-indigo-300 text-xs">{{ $s['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ─── VISI + PROFIL LULUSAN STRIP ──────────────────────────── --}}
@if($prodi->visi || $profilLulusans->isNotEmpty())
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Visi --}}
        @if($prodi->visi)
        <div class="flex items-start gap-3 mb-5">
            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-0.5">Visi</p>
                <p class="text-sm text-gray-700 leading-relaxed italic max-w-3xl">&ldquo;{{ $prodi->visi }}&rdquo;</p>
            </div>
        </div>
        @endif

        {{-- Profil Lulusan preview --}}
        @if($profilLulusans->isNotEmpty())
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Profil Lulusan</p>
            <a href="{{ route('public.profil') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition">
                Lihat profil lengkap →
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
            @foreach($profilLulusans as $pl)
            <a href="{{ route('public.profil') }}"
               class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-xl px-3 py-2.5 transition group">
                <p class="text-xs font-extrabold text-indigo-700">{{ $pl->kode }}</p>
                <p class="text-xs text-gray-600 leading-snug mt-0.5 line-clamp-2 group-hover:text-gray-800 transition">{{ $pl->nama }}</p>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endif

{{-- ─── MAIN CONTENT ─────────────────────────────────────────── --}}
@php
    $semesterList = $mataKuliahs->pluck('semester')->unique()->sort()->values();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{
         tab: 0,
         search: '',
         expanded: null,
         semVisible(sem) {
             return this.tab === 0 || this.tab === sem;
         },
         rowVisible(sem, kode, nama, kons) {
             const q = this.search.trim().toLowerCase();
             const matchSem = this.semVisible(sem);
             if (!matchSem) return false;
             if (!q) return true;
             return kode.toLowerCase().includes(q)
                 || nama.toLowerCase().includes(q)
                 || kons.toLowerCase().includes(q);
         },
         toggle(id) { this.expanded = this.expanded === id ? null : id; }
     }">

    <div class="flex gap-8 items-start">

        {{-- ─── LEFT: MATA KULIAH BROWSER ─────────────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- Toolbar: search + semester tabs --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">

                {{-- Search --}}
                <div class="relative flex-1 max-w-xs">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                         fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           x-model="search"
                           placeholder="Cari kode atau nama MK..."
                           class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400
                                  transition-all"
                           autocomplete="off">
                    <button x-show="search" @click="search=''"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-gray-300 hover:bg-gray-400 flex items-center justify-center transition-colors">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Semester filter chips --}}
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button @click="tab = 0; expanded = null"
                            :class="tab === 0 ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        Semua
                    </button>
                    @foreach($semesterList as $smt)
                    <button @click="tab = {{ $smt }}; expanded = null"
                            :class="tab === {{ $smt }} ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg transition">
                        Smt {{ $smt }}
                    </button>
                    @endforeach
                </div>

                {{-- Result count --}}
                <p class="text-xs text-gray-400 whitespace-nowrap hidden sm:block">
                    {{ $mataKuliahs->count() }} MK total
                </p>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">SKS</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">Smt</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CPL</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">CPMK</th>
                            <th class="px-3 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        @php
                            $relatedCpls = $mk->mkCpls->filter(fn($r) => $r->level !== null)->map(fn($r) => $r->cpl)->filter();
                        @endphp

                        {{-- Main row --}}
                        <tr class="border-b border-gray-50 hover:bg-indigo-50/40 transition-colors cursor-pointer group"
                            x-show="rowVisible({{ $mk->semester }}, '{{ addslashes($mk->kode_rps) }}', '{{ addslashes($mk->nama) }}', '{{ addslashes($mk->konsentrasi ?? '') }}')"
                            x-cloak
                            @click="toggle({{ $mk->id }})">

                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-indigo-600 font-bold">{{ $mk->kode_rps }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors text-sm">
                                    {{ $mk->nama }}
                                </p>
                                @if($mk->konsentrasi)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $mk->konsentrasi }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center text-gray-600 text-sm font-medium">{{ $mk->sks }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                                    {{ $mk->semester }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($relatedCpls as $cpl)
                                    <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 text-xs rounded font-semibold border border-indigo-100">{{ $cpl->kode }}</span>
                                    @endforeach
                                    @if($relatedCpls->isEmpty())
                                    <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($mk->cpmks_count > 0)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700 text-xs font-bold">{{ $mk->cpmks_count }}</span>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 mx-auto"
                                     :class="expanded === {{ $mk->id }} ? 'rotate-90 text-indigo-500' : ''"
                                     fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                </svg>
                            </td>
                        </tr>

                        {{-- Expanded: CPMK list --}}
                        <tr x-show="expanded === {{ $mk->id }}"
                            x-cloak
                            class="bg-indigo-50/60 border-b border-indigo-100">
                            <td colspan="7" class="px-4 py-0">
                                <div class="py-3">
                                    @if($mk->cpmks->isEmpty())
                                    <p class="text-xs text-gray-400 italic py-1">Belum ada CPMK untuk mata kuliah ini.</p>
                                    @else
                                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-2">Capaian Pembelajaran Mata Kuliah (CPMK)</p>
                                    <div class="space-y-1.5">
                                        @foreach($mk->cpmks as $cpmk)
                                        <div class="flex items-start gap-2.5 bg-white rounded-xl px-3 py-2 border border-indigo-100">
                                            <span class="shrink-0 text-xs font-bold text-indigo-600 font-mono pt-0.5 w-16">{{ $cpmk->kode }}</span>
                                            <p class="text-xs text-gray-700 leading-relaxed">{{ $cpmk->deskripsi }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    <div class="mt-3 flex justify-end">
                                        <a href="{{ route('public.mata-kuliah.show', $mk) }}"
                                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition"
                                           @click.stop>
                                            Lihat detail lengkap →
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Empty state --}}
                        <tr x-show="{{ $mataKuliahs->count() > 0 ? 'false' : 'true' }}">
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                Tidak ada mata kuliah yang ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- No search result notice --}}
                <div class="px-4 py-3 border-t border-gray-50 bg-gray-50/50"
                     x-show="search.length > 0">
                    <p class="text-xs text-gray-500">
                        Menampilkan hasil pencarian untuk
                        <strong x-text="'&quot;' + search + '&quot;'"class="text-gray-700"></strong>
                        — klik baris untuk lihat CPMK
                    </p>
                </div>
            </div>

        </div>

        {{-- ─── RIGHT SIDEBAR: CPL ─────────────────────────────── --}}
        <aside class="hidden lg:block w-72 shrink-0">

            {{-- CPL list --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800">CPL Program Studi</h2>
                    <a href="{{ route('public.cpl') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition">
                        Lihat semua →
                    </a>
                </div>
                <div class="divide-y divide-gray-50 max-h-[520px] overflow-y-auto">
                    @foreach($cpls as $cpl)
                    <a href="{{ route('public.cpl') }}#cpl-{{ $cpl->id }}"
                       class="flex items-start gap-3 px-4 py-3 hover:bg-indigo-50/60 transition-colors group">
                        <span class="shrink-0 w-8 h-8 rounded-lg bg-indigo-600 text-white text-xs font-bold flex items-center justify-center
                                     group-hover:bg-indigo-700 transition-colors">
                            {{ $cpl->kode }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-700 leading-snug line-clamp-2 group-hover:text-gray-900 transition-colors">
                                {{ $cpl->deskripsi }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $cpl->mata_kuliahs_count }} MK</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800">Informasi Lain</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach([
                        ['route' => 'public.mata-kuliah', 'label' => 'Daftar Mata Kuliah',    'sub' => $stats['total_mk'].' MK aktif',         'color' => 'bg-indigo-100 text-indigo-600'],
                        ['route' => 'public.cpl',         'label' => 'CPL Program Studi',     'sub' => $stats['total_cpl'].' capaian lulusan',  'color' => 'bg-emerald-100 text-emerald-600'],
                        ['route' => 'public.matriks',     'label' => 'Matriks MK–CPL',        'sub' => 'Peta keterkaitan MK & CPL',             'color' => 'bg-amber-100 text-amber-600'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group">
                        <span class="w-8 h-8 rounded-lg {{ $item['color'] }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $item['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $item['sub'] }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

    </div>{{-- /flex --}}

</div>{{-- /main --}}

@endsection
