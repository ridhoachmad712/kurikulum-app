@extends('layouts.public')
@section('title', 'Mata Kuliah')

@section('content')

{{-- ─── PAGE HEADER ────────────────────────────────────────────── --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span class="text-gray-600 font-medium">Mata Kuliah</span>
        </nav>
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-1">Kurikulum</p>
        <h1 class="text-xl font-bold text-gray-900">Daftar Mata Kuliah</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            {{ $mataKuliahs->flatten()->count() }} mata kuliah aktif &middot;
            {{ $mataKuliahs->flatten()->sum('sks') }} SKS &middot;
            {{ $mataKuliahs->keys()->count() }} semester
        </p>
    </div>
</div>

@php
$konsentrasiList = $mataKuliahs->flatten()->pluck('konsentrasi')->filter()->unique()->sort()->values();
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ tab: {{ $mataKuliahs->keys()->first() ?? 1 }}, kon: '' }">

    {{-- Semester tabs --}}
    <div class="flex flex-wrap gap-2 mb-4 border-b border-gray-100 pb-4">
        @foreach($mataKuliahs as $smt => $mks)
        <button @click="tab = {{ $smt }}"
                :class="tab === {{ $smt }}
                    ? 'bg-blue-700 text-white shadow-sm border-transparent'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:text-blue-700'"
                class="px-4 py-2 text-sm font-medium rounded-xl border transition-all duration-150">
            Semester {{ $smt }}
            <span class="ml-1 opacity-60 text-xs">({{ $mks->count() }} MK)</span>
        </button>
        @endforeach
    </div>

    {{-- Concentration filter --}}
    @if($konsentrasiList->isNotEmpty())
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mr-1">Konsentrasi</span>
        <button @click="kon = ''"
                :class="kon === ''
                    ? 'bg-gray-800 text-white border-gray-800'
                    : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400 hover:text-gray-700'"
                class="px-3 py-1.5 rounded-full border text-xs font-medium transition-all">
            Semua
        </button>
        @foreach($konsentrasiList as $k)
        @php
            $colors = [
                'Manajemen Keuangan'  => ['active' => 'bg-blue-700 text-white border-blue-700',    'idle' => 'bg-white text-blue-600 border-blue-200 hover:border-blue-400 hover:bg-blue-50'],
                'Manajemen Pemasaran' => ['active' => 'bg-emerald-700 text-white border-emerald-700', 'idle' => 'bg-white text-emerald-600 border-emerald-200 hover:border-emerald-400 hover:bg-emerald-50'],
                'Manajemen SDM'       => ['active' => 'bg-violet-700 text-white border-violet-700',  'idle' => 'bg-white text-violet-600 border-violet-200 hover:border-violet-400 hover:bg-violet-50'],
            ];
            $c = $colors[$k] ?? ['active' => 'bg-gray-700 text-white border-gray-700', 'idle' => 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'];
        @endphp
        <button @click="kon = kon === '{{ $k }}' ? '' : '{{ $k }}'"
                :class="kon === '{{ $k }}' ? '{{ $c['active'] }}' : '{{ $c['idle'] }}'"
                class="px-3 py-1.5 rounded-full border text-xs font-medium transition-all">
            {{ $k }}
        </button>
        @endforeach

        {{-- Reset hint when filter active --}}
        <span x-show="kon !== ''" x-cloak
              class="ml-1 text-[11px] text-gray-400 italic">
            · Menampilkan MK Wajib &amp; <span class="font-medium text-gray-600" x-text="kon"></span>
            <button @click="kon = ''" class="ml-1 text-gray-400 hover:text-gray-600 underline text-[11px]">reset</button>
        </span>
    </div>
    @else
    <div class="mb-8"></div>
    @endif

    {{-- Panels per semester --}}
    @foreach($mataKuliahs as $smt => $mks)
    <div x-show="tab === {{ $smt }}" x-cloak>

        {{-- Summary bar --}}
        <div class="flex items-center gap-4 mb-5 text-xs text-gray-500 flex-wrap">
            <span>{{ $mks->count() }} mata kuliah total</span>
            <span>{{ $mks->sum('sks') }} SKS total</span>
            <span>{{ $mks->filter(fn($m) => $m->cpmks_count > 0)->count() }}/{{ $mks->count() }} ber-CPMK</span>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">SKS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CPL Terkait</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">CPMK</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Sub-CPMK</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($mks as $mk)
                    @php $relatedCpls = $mk->mkCpls->pluck('cpl_id')->toArray(); @endphp
                    <tr x-show="kon === '' || '{{ $mk->konsentrasi ?? '' }}' === '' || kon === '{{ $mk->konsentrasi ?? '' }}'"
                        class="hover:bg-blue-50/30 transition-colors duration-100">
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs text-blue-700 font-semibold">{{ $mk->kode_rps }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('public.mata-kuliah.show', $mk) }}"
                               class="font-medium text-gray-800 hover:text-blue-800 transition">
                                {{ $mk->nama }}
                            </a>
                            @if($mk->konsentrasi)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $mk->konsentrasi }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $mk->sks }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($cpls->whereIn('id', $relatedCpls) as $cpl)
                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 text-xs rounded font-medium">{{ $cpl->kode }}</span>
                                @endforeach
                                @if(empty($relatedCpls))
                                <span class="text-xs text-gray-300 italic">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                {{ $mk->cpmks_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $mk->cpmks_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                {{ $mk->sub_cpmks_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $mk->sub_cpmks_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('public.mata-kuliah.show', $mk) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-700 text-white text-xs font-medium rounded-lg hover:bg-blue-800 transition">
                                Detail
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

</div>
@endsection
