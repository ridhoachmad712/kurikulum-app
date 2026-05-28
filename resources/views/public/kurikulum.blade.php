@extends('layouts.public')
@section('title', 'Kurikulum – 12 Tabel OBE')

@section('content')

{{-- ─── PAGE HEADER ────────────────────────────────────────────── --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-1">Dokumen Kurikulum OBE</p>
        <h1 class="text-xl font-bold text-gray-900">12 Tabel Kurikulum</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $prodi->jenjang }} {{ $prodi->nama }} &mdash; {{ $prodi->universitas }}</p>
    </div>
</div>

@php
    $firstMkCpmkId = $mataKuliahsWithCpmk->first()?->id ?? 0;
    $firstMkSubId  = $mataKuliahsWithSub->first()?->id ?? 0;
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{ tab: 1, mkIdCpmk: {{ $firstMkCpmkId }}, mkIdSub: {{ $firstMkSubId }} }">

    {{-- ─── TAB BAR ─────────────────────────────────────────────── --}}
    @php
    $tabGroups = [
        'Matriks MK–CPL' => [
            1  => ['label' => 'Input Level',  'title' => 'Input Level MK–CPL',         'desc' => 'Level keterkaitan setiap Mata Kuliah terhadap CPL (Tinggi / Sedang / Rendah)'],
            2  => ['label' => 'Keterkaitan',  'title' => 'Keterkaitan Biner (0 / 1)',   'desc' => 'Nilai 1 = ada keterkaitan, nilai 0 = tidak terkait'],
            3  => ['label' => 'Nilai Level',  'title' => 'Nilai Level MK–CPL',          'desc' => 'Konversi level ke nilai numerik — Tinggi = 5, Sedang = 3, Rendah = 1'],
            4  => ['label' => '% Global',     'title' => '% Bobot Global MK–CPL',       'desc' => 'Persentase bobot setiap sel terhadap grand total nilai keseluruhan'],
            5  => ['label' => '% per MK',     'title' => '% Bobot per Mata Kuliah',     'desc' => 'Persentase bobot CPL dalam satu mata kuliah (nilai ÷ total nilai MK × 100)'],
        ],
        'CPMK & Sub-CPMK' => [
            6  => ['label' => 'CPMK–CPL',    'title' => 'Keterkaitan CPMK–CPL',         'desc' => 'Tanda V = CPMK mendukung CPL tersebut'],
            7  => ['label' => 'Bobot CPMK',  'title' => 'Bobot CPMK–CPL',              'desc' => 'Bobot persentase CPMK terhadap setiap CPL yang dibebankan'],
            8  => ['label' => 'Rekap Sub',   'title' => 'Rekapitulasi Sub-CPMK',        'desc' => 'Sub-CPMK beserta CLO Tertinggi, Domain, dan Level Taksonomi'],
            9  => ['label' => 'CPMK × Sub',  'title' => 'Keterkaitan CPMK × Sub-CPMK', 'desc' => 'Keterkaitan antara CPMK dengan Sub-CPMK dalam setiap Mata Kuliah'],
            10 => ['label' => 'Sub × CPL',   'title' => 'Keterkaitan Sub-CPMK × CPL',  'desc' => 'Sub-CPMK mewarisi keterkaitan CPL dari CPMK induknya'],
            11 => ['label' => 'Bobot Sub',   'title' => 'Bobot Sub-CPMK dari CPMK',    'desc' => 'Bobot Sub-CPMK = Total Bobot CPMK ÷ Jumlah Sub-CPMK dalam CPMK tersebut'],
            12 => ['label' => 'Rekap Bobot', 'title' => 'Rekap Bobot CPMK × CPL',      'desc' => 'Matriks bobot persentase CPMK × CPL seluruh Mata Kuliah'],
        ],
    ];
    @endphp

    {{-- Tab navigation --}}
    <div class="mb-5 space-y-3">
        @foreach($tabGroups as $groupName => $groupTabs)
        <div>
            {{-- Group label + divider --}}
            <div class="flex items-center gap-3 mb-2">
                <span class="text-[8px] font-semibold uppercase tracking-wider text-gray-400 whitespace-nowrap">{{ $groupName }}</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            {{-- Pill chips --}}
            <div class="flex flex-wrap gap-1.5">
                @foreach($groupTabs as $n => $t)
                <button @click="tab = {{ $n }}"
                        :class="tab === {{ $n }}
                            ? 'bg-blue-700 text-white shadow-sm border-blue-600'
                            : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:text-blue-700 hover:bg-blue-50'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-all whitespace-nowrap">
                    <span :class="tab === {{ $n }} ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400'"
                          class="w-4 h-4 rounded-full text-[9px] font-bold flex items-center justify-center shrink-0 transition-colors">{{ $n }}</span>
                    {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    {{-- Active tab title --}}
    @foreach($tabGroups as $_gn => $groupTabs)
    @foreach($groupTabs as $n => $t)
    <div x-show="tab === {{ $n }}" x-cloak class="flex items-start gap-2 mb-4">
        <div class="w-0.5 self-stretch bg-blue-500 rounded-full mt-0.5 shrink-0"></div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Tabel {{ $n }} — {{ $t['title'] }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $t['desc'] }}</p>
        </div>
    </div>
    @endforeach
    @endforeach

    {{-- ── Selector Mata Kuliah (Tabel 6–12) ─────────────────────── --}}
    <div x-show="tab >= 6" x-cloak class="mb-5">
        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-2">Pilih Mata Kuliah</p>

        {{-- Tabel 6, 7, 12 — filter berdasar CPMK --}}
        <div x-show="tab === 6 || tab === 7 || tab === 12" x-cloak class="flex flex-wrap gap-1.5">
            @forelse($mataKuliahsWithCpmk as $mk)
            <button @click="mkIdCpmk = {{ $mk->id }}"
                    :class="mkIdCpmk === {{ $mk->id }}
                        ? 'bg-blue-700 text-white border-blue-600 shadow-sm'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300 hover:text-blue-700 hover:bg-blue-50'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-all whitespace-nowrap">
                <span :class="mkIdCpmk === {{ $mk->id }} ? 'opacity-60' : 'text-gray-400'"
                      class="font-mono text-[10px] transition-colors">{{ $mk->kode_rps }}</span>
                {{ Str::limit($mk->nama, 30) }}
            </button>
            @empty
            <p class="text-xs text-gray-400 italic">Belum ada mata kuliah dengan CPMK.</p>
            @endforelse
        </div>

        {{-- Tabel 8, 9, 10, 11 — filter berdasar Sub-CPMK --}}
        <div x-show="tab >= 8 && tab <= 11" x-cloak class="flex flex-wrap gap-1.5">
            @forelse($mataKuliahsWithSub as $mk)
            <button @click="mkIdSub = {{ $mk->id }}"
                    :class="mkIdSub === {{ $mk->id }}
                        ? 'bg-blue-700 text-white border-blue-600 shadow-sm'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300 hover:text-blue-700 hover:bg-blue-50'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-all whitespace-nowrap">
                <span :class="mkIdSub === {{ $mk->id }} ? 'opacity-60' : 'text-gray-400'"
                      class="font-mono text-[10px] transition-colors">{{ $mk->kode_rps }}</span>
                {{ Str::limit($mk->nama, 30) }}
            </button>
            @empty
            <p class="text-xs text-gray-400 italic">Belum ada mata kuliah dengan Sub-CPMK.</p>
            @endforelse
        </div>
    </div>

    {{-- ─── TABEL 1: Input Level ───────────────────────────────── --}}
    <div x-show="tab === 1" x-cloak>
        <p class="text-xs text-gray-500 mb-3">
            Keterangan level: &nbsp;
            <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded font-medium">T = Tinggi</span>&nbsp;
            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded font-medium">S = Sedang</span>&nbsp;
            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded font-medium">R = Rendah</span>
        </p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)
                            <th class="px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100">
                                <span class="font-mono text-blue-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
                                @if($mk->konsentrasi)<span class="ml-1 text-[9px] text-violet-500 font-medium opacity-80">[{{ $mk->konsentrasi }}]</span>@endif
                            </td>
                            @foreach($cpls as $cpl)
                            @php $level = $matriksInput[$mk->id][$cpl->id] ?? null; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($level === 'Tinggi')
                                    <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-medium">T</span>
                                @elseif($level === 'Sedang')
                                    <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded text-[10px] font-medium">S</span>
                                @elseif($level === 'Rendah')
                                    <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded text-[10px] font-medium">R</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 2: Keterkaitan 0/1 ──────────────────────────── --}}
    <div x-show="tab === 2" x-cloak>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-blue-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        @php $rowTotal = 0; @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-blue-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}@if($mk->konsentrasi)<span class="ml-1 text-[9px] text-violet-500 font-medium opacity-80">[{{ $mk->konsentrasi }}]</span>@endif</td>
                            @foreach($cpls as $cpl)
                            @php $val = $binary[$mk->id][$cpl->id] ?? 0; $rowTotal += $val; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val)<span class="font-bold text-blue-800">1</span>@else<span class="text-gray-300">0</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-blue-50 text-blue-800">{{ $rowTotal }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td colspan="2" class="px-3 py-2 text-center">Jumlah MK</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $binaryColTotals[$cpl->id] ?? 0 }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ array_sum($binaryColTotals) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 3: Nilai Level ───────────────────────────────── --}}
    <div x-show="tab === 3" x-cloak>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-blue-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-blue-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}@if($mk->konsentrasi)<span class="ml-1 text-[9px] text-violet-500 font-medium opacity-80">[{{ $mk->konsentrasi }}]</span>@endif</td>
                            @foreach($cpls as $cpl)
                            @php $val = $nilaiMatrix[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="font-bold text-blue-800">{{ $val }}</span>@else<span class="text-gray-300">0</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-blue-50 text-blue-800">{{ $rowTotals[$mk->id] ?? 0 }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td colspan="2" class="px-3 py-2 text-center">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $colTotals[$cpl->id] ?? 0 }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ $grandTotal }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 4: % Bobot Global ────────────────────────────── --}}
    <div x-show="tab === 4" x-cloak>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>@endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-blue-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}@if($mk->konsentrasi)<span class="ml-1 text-[9px] text-violet-500 font-medium opacity-80">[{{ $mk->konsentrasi }}]</span>@endif</td>
                            @foreach($cpls as $cpl)
                            @php $val = $pctGlobal[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="text-blue-700 font-medium">{{ round($val,1) }}%</span>@else<span class="text-gray-300">—</span>@endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td colspan="2" class="px-3 py-2 text-center">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ round($pctGlobalColTotals[$cpl->id] ?? 0, 1) }}%</td>@endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 5: % Bobot per MK ───────────────────────────── --}}
    <div x-show="tab === 5" x-cloak>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-blue-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-blue-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}@if($mk->konsentrasi)<span class="ml-1 text-[9px] text-violet-500 font-medium opacity-80">[{{ $mk->konsentrasi }}]</span>@endif</td>
                            @foreach($cpls as $cpl)
                            @php $val = $pctPerMk[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="text-blue-700 font-medium">{{ $val }}%</span>@else<span class="text-gray-300">—</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-blue-50 text-blue-800">
                                {{ ($rowTotals[$mk->id] ?? 0) > 0 ? '100%' : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 6: Keterkaitan CPMK–CPL ────────────────────── --}}
    <div x-show="tab === 6" x-cloak>
        @forelse($mataKuliahsWithCpmk as $mk)
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdCpmk === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="text-xs text-blue-300 ml-auto">{{ $mk->cpmks->count() }} CPMK</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-16 text-gray-600" title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>@endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr class="bg-blue-50">
                            <td class="px-4 py-2 font-semibold text-blue-800 italic text-xs">{{ $mk->nama }} (semua CPMK)</td>
                            @foreach($cpls as $cpl)
                            @php $mkHas = $mk->cpmks->contains(fn($c) => $c->cpmkCpls->where('cpl_id',$cpl->id)->where('has_relation',true)->isNotEmpty()); @endphp
                            <td class="px-2 py-2 text-center">@if($mkHas)<span class="text-blue-700 font-bold">V</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                        </tr>
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-blue-700 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 80) }}</td>
                            @foreach($cpls as $cpl)
                            @php $has = $cpmk->cpmkCpls->where('cpl_id',$cpl->id)->where('has_relation',true)->isNotEmpty(); @endphp
                            <td class="px-2 py-2 text-center">@if($has)<span class="text-green-600 font-bold">V</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada CPMK yang diisi.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 7: Bobot CPMK–CPL ──────────────────────────── --}}
    <div x-show="tab === 7" x-cloak>
        @forelse($mataKuliahsWithCpmk as $mk)
        @php $mkCplTotals = []; foreach($cpls as $cpl){ $mkCplTotals[$cpl->id] = 0; } @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdCpmk === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20 text-gray-600">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-20 bg-blue-50 text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mk->cpmks as $cpmk)
                        @php $rowTotal = 0; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-blue-700 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 80) }}</td>
                            @foreach($cpls as $cpl)
                            @php $cc = $cpmk->cpmkCpls->firstWhere('cpl_id',$cpl->id); $bobot = $cc ? (float)$cc->bobot_persen : 0; $rowTotal += $bobot; $mkCplTotals[$cpl->id] += $bobot; @endphp
                            <td class="px-2 py-2 text-center">@if($bobot>0)<span class="text-blue-700 font-medium">{{ round($bobot) }}%</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                            <td class="px-2 py-2 text-center font-bold bg-blue-50 {{ abs($rowTotal-100)<0.1&&$rowTotal>0?'text-green-600':($rowTotal>0?'text-amber-600':'text-gray-300') }}">
                                {{ $rowTotal>0?round($rowTotal).'%':'—' }}
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td class="px-4 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $mkCplTotals[$cpl->id]>0?round($mkCplTotals[$cpl->id]).'%':'—' }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ round(array_sum($mkCplTotals)) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada data CPMK.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 8: Rekap Sub-CPMK ───────────────────────────── --}}
    <div x-show="tab === 8" x-cloak>
        @forelse($mataKuliahsWithSub as $mk)
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdSub === {{ $mk->id }}">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <span class="font-mono text-xs text-blue-700 mr-2">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-gray-800">{{ $mk->nama }}</span>
                <span class="ml-2 text-xs text-gray-400">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                            <th class="border border-gray-200 px-3 py-2 text-left w-32">CPMK</th>
                            <th class="border border-gray-200 px-3 py-2 text-left w-36">Sub-CPMK</th>
                            <th class="border border-gray-200 px-3 py-2 text-left">Deskripsi</th>
                            <th class="border border-gray-200 px-3 py-2 text-center w-28">CLO Tertinggi</th>
                            <th class="border border-gray-200 px-3 py-2 text-center w-24">Domain</th>
                            <th class="border border-gray-200 px-3 py-2 text-center w-20">Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mk->cpmks as $cpmk)
                            @foreach($cpmk->subCpmks as $sc)
                            @php $clo = $sc->subCpmkClos->where('is_tertinggi',true)->first()?->cloTaxonomi; @endphp
                            <tr class="hover:bg-gray-50">
                                @if($loop->first)
                                <td class="border border-gray-200 px-3 py-2 font-semibold text-blue-700 align-top" rowspan="{{ $cpmk->subCpmks->count() }}">{{ $cpmk->kode }}</td>
                                @endif
                                <td class="border border-gray-200 px-3 py-2 font-medium text-teal-700">{{ $sc->kode }}</td>
                                <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $sc->deskripsi }}</td>
                                <td class="border border-gray-200 px-3 py-2 text-center">
                                    @if($clo)<span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded font-mono font-semibold">{{ $clo->kode }}</span>@else<span class="text-gray-300">–</span>@endif
                                </td>
                                <td class="border border-gray-200 px-3 py-2 text-center text-gray-500">{{ $clo?->domain ?? '–' }}</td>
                                <td class="border border-gray-200 px-3 py-2 text-center text-gray-500">{{ $clo ? 'L'.$clo->level : '–' }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada Sub-CPMK.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 9: Keterkaitan CPMK × Sub-CPMK ─────────────── --}}
    <div x-show="tab === 9" x-cloak>
        @forelse($mataKuliahsWithSub as $mk)
        @php $allSubs = $mk->cpmks->flatMap->subCpmks->unique('id')->sortBy('urutan')->values(); @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdSub === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-blue-300">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[220px] text-gray-600">CPMK</th>
                            @foreach($allSubs as $sub)<th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">{{ $sub->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-blue-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-3 py-2 text-gray-700"><span class="font-semibold text-blue-700 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 70) }}</td>
                            @foreach($allSubs as $sub)
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                @if($cpmk->subCpmks->contains('id',$sub->id))<span class="text-green-600 font-bold text-sm">V</span>@else<span class="text-gray-200">—</span>@endif
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-blue-50 text-blue-800">{{ $cpmk->subCpmks->count() }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold text-xs">
                            <td class="border border-blue-800 px-3 py-2">Jumlah CPMK terkait</td>
                            @foreach($allSubs as $sub)
                            @php $linkedCount = $mk->cpmks->filter(fn($c) => $c->subCpmks->contains('id', $sub->id))->count(); @endphp
                            <td class="border border-blue-800 px-2 py-2 text-center">{{ $linkedCount }}</td>
                            @endforeach
                            <td class="border border-blue-800 px-2 py-2 text-center">{{ $allSubs->count() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada Sub-CPMK.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 10: Sub-CPMK × CPL ─────────────────────────── --}}
    <div x-show="tab === 10" x-cloak>
        @forelse($mataKuliahsWithSub as $mk)
        @php
            $cpmkCplMap2 = [];
            foreach($mk->cpmks as $cpmk){ $cpmkCplMap2[$cpmk->id] = $cpmk->cpmkCpls->where('has_relation',true)->pluck('cpl_id')->toArray(); }
            $cplTotal = array_fill_keys($cpls->pluck('id')->toArray(), 0);
        @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdSub === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-blue-300">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left w-28 text-gray-600">Sub-CPMK</th>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600">Deskripsi</th>
                            @foreach($cpls as $cpl)<th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600" title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-blue-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mk->cpmks as $cpmk)
                            @foreach($cpmk->subCpmks as $sub)
                            @php $relIds = $cpmkCplMap2[$cpmk->id] ?? []; $subTotal = 0; foreach($relIds as $cid){ $cplTotal[$cid] = ($cplTotal[$cid]??0)+1; $subTotal++; } @endphp
                            <tr class="hover:bg-gray-50">
                                @if($loop->first)
                                <td class="border border-gray-200 px-3 py-2 font-semibold text-blue-700 align-middle text-center" rowspan="{{ $cpmk->subCpmks->count() }}">{{ $cpmk->kode }}</td>
                                @endif
                                <td class="border border-gray-200 px-3 py-2 text-teal-700 font-medium">{{ $sub->kode }}</td>
                                @foreach($cpls as $cpl)
                                <td class="border border-gray-200 px-2 py-2 text-center">@if(in_array($cpl->id,$relIds))<span class="text-green-600 font-bold text-sm">V</span>@else<span class="text-gray-200">—</span>@endif</td>
                                @endforeach
                                <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-blue-50 text-blue-800">{{ $subTotal }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td colspan="2" class="border border-blue-800 px-3 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="border border-blue-800 px-2 py-2 text-center">{{ $cplTotal[$cpl->id]>0?$cplTotal[$cpl->id]:'—' }}</td>@endforeach
                            <td class="border border-blue-800 px-2 py-2 text-center">{{ array_sum($cplTotal) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada Sub-CPMK.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 11: % Bobot CPMK & Sub-CPMK ────────────────── --}}
    <div x-show="tab === 11" x-cloak>
        @forelse($mataKuliahsWithSub as $mk)
        @php
            $allSubs = $mk->cpmks->flatMap->subCpmks->unique('id')->sortBy('urutan')->values();
            $mkTotal = $mk->cpmks->sum('total_bobot');
            // Build pivot-based bobot maps
            $cpmkSubBobotMap = [];
            $bobotPerSubMap  = [];
            foreach ($mk->cpmks as $cpmk) {
                $cpmkSubBobotMap[$cpmk->id] = [];
                foreach ($cpmk->subCpmks as $s) {
                    $b = $s->pivot->bobot !== null ? (float)$s->pivot->bobot : (float)$cpmk->bobot_per_sub;
                    $cpmkSubBobotMap[$cpmk->id][$s->id] = $b;
                    $bobotPerSubMap[$s->id] = $b;
                }
            }
        @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdSub === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-blue-300">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[220px] text-gray-600">CPMK</th>
                            @foreach($allSubs as $sub)<th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">{{ $sub->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-amber-50 text-gray-600">Total</th>
                            <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-blue-50 text-gray-600">Tabel 7</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-3 py-2 text-gray-700"><span class="font-semibold text-blue-700 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi,70) }}</td>
                            @foreach($allSubs as $sub)
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                @php $bobotCell = $cpmkSubBobotMap[$cpmk->id][$sub->id] ?? null; @endphp
                                @if($bobotCell !== null && $bobotCell > 0)<span class="text-blue-700 font-medium">{{ round($bobotCell) }}%</span>@else<span class="text-gray-200">—</span>@endif
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-amber-50 {{ $cpmk->total_bobot>0?'text-amber-700':'text-gray-300' }}">{{ $cpmk->total_bobot>0?round($cpmk->total_bobot).'%':'—' }}</td>
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-blue-50 {{ $cpmk->total_bobot>0?'text-blue-800':'text-gray-300' }}">{{ $cpmk->total_bobot>0?round($cpmk->total_bobot).'%':'—' }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td class="border border-blue-800 px-3 py-2">Total</td>
                            @foreach($allSubs as $sub)
                            @php $v = $bobotPerSubMap[$sub->id] ?? 0; @endphp
                            <td class="border border-blue-800 px-2 py-2 text-center text-xs">{{ $v>0?round($v).'%':'—' }}</td>
                            @endforeach
                            <td class="border border-blue-800 px-2 py-2 text-center bg-amber-900">{{ round($mkTotal) }}%</td>
                            <td class="border border-blue-800 px-2 py-2 text-center">{{ round($mkTotal) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Bobot Asesmen per Sub-CPMK + Validasi --}}
            @php
                $subsWithAsesmen = $allSubs->filter(fn($s) => $s->asesmen);
                $bobotPerSub     = $bobotPerSubMap; // already built with pivot-aware bobot above
            @endphp
            @if($subsWithAsesmen->isNotEmpty())
            <div class="border-t border-gray-200">
                <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-emerald-500 inline-block"></span>
                    <p class="text-xs font-semibold text-gray-600">Bobot Asesmen per Sub-CPMK</p>
                    <span class="ml-auto text-[10px] text-gray-400">Jumlah Asesmen harus = Bobot Teoritis</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="text-xs w-full border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-200 px-3 py-2 text-left w-28 text-gray-600">Sub-CPMK</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">Tugas</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">Kuis</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-24 text-gray-600">Kasus/Proyek</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">UTS</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">UAS</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-emerald-50 text-gray-600 font-semibold">Jumlah</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-24 bg-blue-50 text-gray-600">Bobot Teoritis</th>
                                <th class="border border-gray-200 px-2 py-2 text-center w-24 text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($subsWithAsesmen as $sub)
                            @php
                                $a       = $sub->asesmen;
                                $total   = $a->tugas + $a->quis + $a->kasus_proyek + $a->uts + $a->uas;
                                $teoritis = $bobotPerSub[$sub->id] ?? 0;
                                $selisih  = abs($total - $teoritis);
                                $selaras  = $teoritis > 0 && $selisih <= 0.5;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-200 px-3 py-1.5 font-medium text-teal-700">{{ $sub->kode }}</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">@if($a->tugas > 0)<span class="text-gray-700">{{ $a->tugas }}</span>@else<span class="text-gray-300">—</span>@endif</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">@if($a->quis > 0)<span class="text-gray-700">{{ $a->quis }}</span>@else<span class="text-gray-300">—</span>@endif</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">@if($a->kasus_proyek > 0)<span class="text-gray-700">{{ $a->kasus_proyek }}</span>@else<span class="text-gray-300">—</span>@endif</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">@if($a->uts > 0)<span class="text-gray-700">{{ $a->uts }}</span>@else<span class="text-gray-300">—</span>@endif</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">@if($a->uas > 0)<span class="text-gray-700">{{ $a->uas }}</span>@else<span class="text-gray-300">—</span>@endif</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center font-bold bg-emerald-50 {{ $total > 0 ? 'text-emerald-700' : 'text-gray-300' }}">{{ $total > 0 ? $total : '—' }}</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center bg-blue-50 text-blue-800 font-medium">{{ $teoritis > 0 ? round($teoritis).'%' : '—' }}</td>
                                <td class="border border-gray-200 px-2 py-1.5 text-center">
                                    @if($teoritis <= 0)
                                        <span class="text-gray-300">—</span>
                                    @elseif($selaras)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold text-[10px]">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                            Selaras
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold text-[10px]" title="Selisih: {{ round($selisih) }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                            {{ $total > $teoritis ? '+' : '' }}{{ round($total - $teoritis) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada data Sub-CPMK dengan bobot.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 12: Rekap Bobot CPMK–CPL ───────────────────── --}}
    <div x-show="tab === 12" x-cloak>
        @forelse($mataKuliahsWithCpmk->filter(fn($mk)=>$mk->cpmks->some(fn($c)=>$c->cpmkCpls->isNotEmpty())) as $mk)
        @php $cplTotals12 = array_fill_keys($cpls->pluck('id')->toArray(), 0); @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
             x-show="mkIdCpmk === {{ $mk->id }}">
            <div class="bg-blue-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-blue-200">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20 text-gray-600">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-20 bg-blue-50 text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mk->cpmks as $cpmk)
                        @php $rowTotal12 = 0; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-blue-700 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi,80) }}</td>
                            @foreach($cpls as $cpl)
                            @php $cc = $cpmk->cpmkCpls->firstWhere('cpl_id',$cpl->id); $b = $cc?(float)$cc->bobot_persen:0; $rowTotal12+=$b; $cplTotals12[$cpl->id]+=$b; @endphp
                            <td class="px-2 py-2 text-center">@if($b>0)<span class="text-blue-700 font-medium">{{ round($b) }}%</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                            <td class="px-2 py-2 text-center font-bold bg-blue-50 {{ abs($rowTotal12-100)<0.1&&$rowTotal12>0?'text-green-600':($rowTotal12>0?'text-amber-600':'text-gray-300') }}">{{ $rowTotal12>0?round($rowTotal12).'%':'—' }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-blue-900 text-white font-bold">
                            <td class="px-4 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $cplTotals12[$cpl->id]>0?round($cplTotals12[$cpl->id]).'%':'—' }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ round(array_sum($cplTotals12)) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada data CPMK dengan bobot CPL.</div>
        @endforelse
    </div>

</div>
@endsection
