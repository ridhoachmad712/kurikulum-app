@extends('layouts.public')
@section('title', 'Kurikulum – 12 Tabel OBE')

@section('content')

{{-- ─── HERO ──────────────────────────────────────────────────── --}}
<div class="bg-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <p class="text-indigo-300 text-xs font-semibold uppercase tracking-widest mb-1">Dokumen Kurikulum OBE</p>
        <h1 class="text-2xl font-extrabold">12 Tabel Kurikulum</h1>
        <p class="text-indigo-200 text-sm mt-1">{{ $prodi->jenjang }} {{ $prodi->nama }} &mdash; {{ $prodi->universitas }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
     x-data="{ tab: 1 }">

    {{-- ─── TAB BAR ─────────────────────────────────────────────── --}}
    @php
    $tabs = [
        1  => ['title'=>'Input Level',        'short'=>'Tabel 1'],
        2  => ['title'=>'Keterkaitan (0/1)',   'short'=>'Tabel 2'],
        3  => ['title'=>'Nilai Level',         'short'=>'Tabel 3'],
        4  => ['title'=>'% Bobot Global',      'short'=>'Tabel 4'],
        5  => ['title'=>'% Bobot per MK',      'short'=>'Tabel 5'],
        6  => ['title'=>'Keterkaitan CPMK–CPL','short'=>'Tabel 6'],
        7  => ['title'=>'Bobot CPMK–CPL',      'short'=>'Tabel 7'],
        8  => ['title'=>'Rekap Sub-CPMK',      'short'=>'Tabel 8'],
        9  => ['title'=>'CPMK & Sub-CPMK',    'short'=>'Tabel 9'],
        10 => ['title'=>'Sub-CPMK & CPL',     'short'=>'Tabel 10'],
        11 => ['title'=>'Bobot Sub-CPMK',     'short'=>'Tabel 11'],
        12 => ['title'=>'Rekap Bobot',         'short'=>'Tabel 12'],
    ];
    $row1 = array_slice($tabs, 0, 5,  true);
    $row2 = array_slice($tabs, 5, 7,  true);
    @endphp

    <div class="bg-gray-100 rounded-2xl p-1.5 mb-6 space-y-1">
        {{-- Baris 1 --}}
        <div class="grid grid-cols-5 gap-1">
            @foreach($row1 as $n => $t)
            <button @click="tab = {{ $n }}"
                    :class="tab === {{ $n }}
                        ? 'bg-white shadow text-indigo-700 font-semibold'
                        : 'text-gray-500 hover:text-gray-700 hover:bg-white/60'"
                    class="flex items-center justify-center gap-1.5 px-2 py-2 rounded-xl text-xs transition-all">
                <span :class="tab === {{ $n }} ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'"
                      class="w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0 transition-colors">{{ $n }}</span>
                <span class="truncate">{{ $t['title'] }}</span>
            </button>
            @endforeach
        </div>
        {{-- Baris 2 --}}
        <div class="grid grid-cols-7 gap-1">
            @foreach($row2 as $n => $t)
            <button @click="tab = {{ $n }}"
                    :class="tab === {{ $n }}
                        ? 'bg-white shadow text-indigo-700 font-semibold'
                        : 'text-gray-500 hover:text-gray-700 hover:bg-white/60'"
                    class="flex items-center justify-center gap-1.5 px-2 py-2 rounded-xl text-xs transition-all">
                <span :class="tab === {{ $n }} ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'"
                      class="w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center shrink-0 transition-colors">{{ $n }}</span>
                <span class="truncate hidden sm:inline">{{ $t['title'] }}</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- ─── TABEL 1: Input Level ───────────────────────────────── --}}
    <div x-show="tab === 1" x-cloak>
        <p class="text-xs text-gray-500 mb-3">Level keterkaitan setiap Mata Kuliah terhadap CPL: <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded font-medium">Tinggi</span> <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded font-medium">Sedang</span> <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded font-medium">Rendah</span></p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
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
                                <span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
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
        <p class="text-xs text-gray-500 mb-3">Nilai <strong>1</strong> = ada keterkaitan, <strong>0</strong> = tidak terkait.</p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-indigo-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        @php $rowTotal = 0; @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}</td>
                            @foreach($cpls as $cpl)
                            @php $val = $binary[$mk->id][$cpl->id] ?? 0; $rowTotal += $val; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val)<span class="font-bold text-indigo-700">1</span>@else<span class="text-gray-300">0</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-indigo-50 text-indigo-700">{{ $rowTotal }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
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
        <p class="text-xs text-gray-500 mb-3">Tinggi=5, Sedang=3, Rendah=1, Tidak terkait=0.</p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-indigo-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}</td>
                            @foreach($cpls as $cpl)
                            @php $val = $nilaiMatrix[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="font-bold text-indigo-700">{{ $val }}</span>@else<span class="text-gray-300">0</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-indigo-50 text-indigo-700">{{ $rowTotals[$mk->id] ?? 0 }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
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
        <p class="text-xs text-gray-500 mb-3">% bobot setiap sel terhadap grand total nilai.</p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>@endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}</td>
                            @foreach($cpls as $cpl)
                            @php $val = $pctGlobal[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="text-blue-700 font-medium">{{ number_format($val,2) }}%</span>@else<span class="text-gray-300">—</span>@endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td colspan="2" class="px-3 py-2 text-center">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ number_format($pctGlobalColTotals[$cpl->id] ?? 0, 2) }}%</td>@endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ─── TABEL 5: % Bobot per MK ───────────────────────────── --}}
    <div x-show="tab === 5" x-cloak>
        <p class="text-xs text-gray-500 mb-3">% bobot CPL dalam satu MK (nilai / total nilai MK × 100).</p>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-xs border-collapse w-full">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th class="px-3 py-2 text-left w-8">#</th>
                            <th class="px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-16 bg-indigo-800">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mataKuliahs as $mk)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                            <td class="px-3 py-1.5 border-r border-gray-100"><span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}</td>
                            @foreach($cpls as $cpl)
                            @php $val = $pctPerMk[$mk->id][$cpl->id] ?? 0; @endphp
                            <td class="px-2 py-1.5 text-center">
                                @if($val > 0)<span class="text-blue-700 font-medium">{{ number_format($val,1) }}%</span>@else<span class="text-gray-300">—</span>@endif
                            </td>
                            @endforeach
                            <td class="px-2 py-1.5 text-center font-bold bg-indigo-50 text-indigo-700">
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
        <p class="text-xs text-gray-500 mb-3">Tanda <strong>V</strong> = CPMK mendukung CPL tersebut.</p>
        @forelse($mataKuliahsWithCpmk as $mk)
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="text-xs text-indigo-400 ml-auto">{{ $mk->cpmks->count() }} CPMK</span>
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
                        <tr class="bg-indigo-50">
                            <td class="px-4 py-2 font-semibold text-indigo-700 italic text-xs">{{ $mk->nama }} (semua CPMK)</td>
                            @foreach($cpls as $cpl)
                            @php $mkHas = $mk->cpmks->contains(fn($c) => $c->cpmkCpls->where('cpl_id',$cpl->id)->where('has_relation',true)->isNotEmpty()); @endphp
                            <td class="px-2 py-2 text-center">@if($mkHas)<span class="text-indigo-600 font-bold">V</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                        </tr>
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 80) }}</td>
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
        <p class="text-xs text-gray-500 mb-3">Bobot persentase CPMK terhadap CPL yang dibebankan.</p>
        @forelse($mataKuliahsWithCpmk as $mk)
        @php $mkCplTotals = []; foreach($cpls as $cpl){ $mkCplTotals[$cpl->id] = 0; } @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20 text-gray-600">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-20 bg-indigo-50 text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mk->cpmks as $cpmk)
                        @php $rowTotal = 0; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 80) }}</td>
                            @foreach($cpls as $cpl)
                            @php $cc = $cpmk->cpmkCpls->firstWhere('cpl_id',$cpl->id); $bobot = $cc ? (float)$cc->bobot_persen : 0; $rowTotal += $bobot; $mkCplTotals[$cpl->id] += $bobot; @endphp
                            <td class="px-2 py-2 text-center">@if($bobot>0)<span class="text-blue-700 font-medium">{{ number_format($bobot,2) }}%</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                            <td class="px-2 py-2 text-center font-bold bg-indigo-50 {{ abs($rowTotal-100)<0.1&&$rowTotal>0?'text-green-600':($rowTotal>0?'text-amber-600':'text-gray-300') }}">
                                {{ $rowTotal>0?number_format($rowTotal,1).'%':'—' }}
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td class="px-4 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $mkCplTotals[$cpl->id]>0?number_format($mkCplTotals[$cpl->id],1).'%':'—' }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ number_format(array_sum($mkCplTotals),1) }}%</td>
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
        <p class="text-xs text-gray-500 mb-3">Rekapitulasi Sub-CPMK beserta CLO tertinggi.</p>
        @forelse($mataKuliahsWithSub as $mk)
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <span class="font-mono text-xs text-indigo-600 mr-2">{{ $mk->kode_rps }}</span>
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
                                <td class="border border-gray-200 px-3 py-2 font-semibold text-indigo-600 align-top" rowspan="{{ $cpmk->subCpmks->count() }}">{{ $cpmk->kode }}</td>
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
        <p class="text-xs text-gray-500 mb-3">Keterkaitan CPMK dengan Sub-CPMK dalam setiap Mata Kuliah.</p>
        @forelse($mataKuliahsWithSub as $mk)
        @php $allSubs = $mk->cpmks->flatMap->subCpmks->sortBy('urutan')->values(); @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-indigo-400">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[220px] text-gray-600">CPMK</th>
                            @foreach($allSubs as $sub)<th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">{{ $sub->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-indigo-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-3 py-2 text-gray-700"><span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi, 70) }}</td>
                            @foreach($allSubs as $sub)
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                @if($sub->cpmk_id===$cpmk->id)<span class="text-green-600 font-bold text-sm">V</span>@else<span class="text-gray-200">—</span>@endif
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-indigo-50 text-indigo-700">{{ $cpmk->subCpmks->count() }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold text-xs">
                            <td class="border border-indigo-800 px-3 py-2">Total Sub-CPMK</td>
                            @foreach($allSubs as $sub)<td class="border border-indigo-800 px-2 py-2 text-center">1</td>@endforeach
                            <td class="border border-indigo-800 px-2 py-2 text-center">{{ $allSubs->count() }}</td>
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
        <p class="text-xs text-gray-500 mb-3">Keterkaitan Sub-CPMK dengan CPL (diwarisi dari CPMK induk).</p>
        @forelse($mataKuliahsWithSub as $mk)
        @php
            $cpmkCplMap2 = [];
            foreach($mk->cpmks as $cpmk){ $cpmkCplMap2[$cpmk->id] = $cpmk->cpmkCpls->where('has_relation',true)->pluck('cpl_id')->toArray(); }
            $cplTotal = array_fill_keys($cpls->pluck('id')->toArray(), 0);
        @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-indigo-400">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left w-28 text-gray-600">Sub-CPMK</th>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600">Deskripsi</th>
                            @foreach($cpls as $cpl)<th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600" title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-indigo-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mk->cpmks as $cpmk)
                            @foreach($cpmk->subCpmks as $sub)
                            @php $relIds = $cpmkCplMap2[$cpmk->id] ?? []; $subTotal = 0; foreach($relIds as $cid){ $cplTotal[$cid] = ($cplTotal[$cid]??0)+1; $subTotal++; } @endphp
                            <tr class="hover:bg-gray-50">
                                @if($loop->first)
                                <td class="border border-gray-200 px-3 py-2 font-semibold text-indigo-600 align-middle text-center" rowspan="{{ $cpmk->subCpmks->count() }}">{{ $cpmk->kode }}</td>
                                @endif
                                <td class="border border-gray-200 px-3 py-2 text-teal-700 font-medium">{{ $sub->kode }}</td>
                                @foreach($cpls as $cpl)
                                <td class="border border-gray-200 px-2 py-2 text-center">@if(in_array($cpl->id,$relIds))<span class="text-green-600 font-bold text-sm">V</span>@else<span class="text-gray-200">—</span>@endif</td>
                                @endforeach
                                <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-indigo-50 text-indigo-700">{{ $subTotal }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td colspan="2" class="border border-indigo-800 px-3 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="border border-indigo-800 px-2 py-2 text-center">{{ $cplTotal[$cpl->id]>0?$cplTotal[$cpl->id]:'—' }}</td>@endforeach
                            <td class="border border-indigo-800 px-2 py-2 text-center">{{ array_sum($cplTotal) }}</td>
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
        <p class="text-xs text-gray-500 mb-3">Bobot Sub-CPMK = Total Bobot CPMK ÷ Jumlah Sub-CPMK dalam CPMK tersebut.</p>
        @forelse($mataKuliahsWithSub as $mk)
        @php $allSubs = $mk->cpmks->flatMap->subCpmks->sortBy('urutan')->values(); $mkTotal = $mk->cpmks->sum('total_bobot'); @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
                <span class="ml-auto text-xs text-indigo-400">Smt {{ $mk->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[220px] text-gray-600">CPMK</th>
                            @foreach($allSubs as $sub)<th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">{{ $sub->kode }}</th>@endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-amber-50 text-gray-600">Total</th>
                            <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-indigo-50 text-gray-600">Tabel 7</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mk->cpmks as $cpmk)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-3 py-2 text-gray-700"><span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi,70) }}</td>
                            @foreach($allSubs as $sub)
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                @if($sub->cpmk_id===$cpmk->id && $cpmk->bobot_per_sub>0)<span class="text-blue-700 font-medium">{{ number_format($cpmk->bobot_per_sub,2) }}%</span>@else<span class="text-gray-200">—</span>@endif
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-amber-50 {{ $cpmk->total_bobot>0?'text-amber-700':'text-gray-300' }}">{{ $cpmk->total_bobot>0?number_format($cpmk->total_bobot,2).'%':'—' }}</td>
                            <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-indigo-50 {{ $cpmk->total_bobot>0?'text-indigo-700':'text-gray-300' }}">{{ $cpmk->total_bobot>0?number_format($cpmk->total_bobot,2).'%':'—' }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td class="border border-indigo-800 px-3 py-2">Total</td>
                            @foreach($allSubs as $sub)
                            @php $pc = $mk->cpmks->firstWhere('id',$sub->cpmk_id); $v = $pc?->bobot_per_sub ?? 0; @endphp
                            <td class="border border-indigo-800 px-2 py-2 text-center text-xs">{{ $v>0?number_format($v,1).'%':'—' }}</td>
                            @endforeach
                            <td class="border border-indigo-800 px-2 py-2 text-center bg-amber-900">{{ number_format($mkTotal,1) }}%</td>
                            <td class="border border-indigo-800 px-2 py-2 text-center">{{ number_format($mkTotal,1) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">Belum ada data Sub-CPMK dengan bobot.</div>
        @endforelse
    </div>

    {{-- ─── TABEL 12: Rekap Bobot CPMK–CPL ───────────────────── --}}
    <div x-show="tab === 12" x-cloak>
        <p class="text-xs text-gray-500 mb-3">Matriks bobot % CPMK × CPL seluruh mata kuliah.</p>
        @forelse($mataKuliahsWithCpmk->filter(fn($mk)=>$mk->cpmks->some(fn($c)=>$c->cpmkCpls->isNotEmpty())) as $mk)
        @php $cplTotals12 = array_fill_keys($cpls->pluck('id')->toArray(), 0); @endphp
        <div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
                <span class="font-semibold text-sm">{{ $mk->nama }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                            @foreach($cpls as $cpl)<th class="px-2 py-2 text-center w-20 text-gray-600">{{ $cpl->kode }}</th>@endforeach
                            <th class="px-2 py-2 text-center w-20 bg-indigo-50 text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mk->cpmks as $cpmk)
                        @php $rowTotal12 = 0; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-700"><span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>{{ Str::limit($cpmk->deskripsi,80) }}</td>
                            @foreach($cpls as $cpl)
                            @php $cc = $cpmk->cpmkCpls->firstWhere('cpl_id',$cpl->id); $b = $cc?(float)$cc->bobot_persen:0; $rowTotal12+=$b; $cplTotals12[$cpl->id]+=$b; @endphp
                            <td class="px-2 py-2 text-center">@if($b>0)<span class="text-blue-700 font-medium">{{ number_format($b,2) }}%</span>@else<span class="text-gray-200">—</span>@endif</td>
                            @endforeach
                            <td class="px-2 py-2 text-center font-bold bg-indigo-50 {{ abs($rowTotal12-100)<0.1&&$rowTotal12>0?'text-green-600':($rowTotal12>0?'text-amber-600':'text-gray-300') }}">{{ $rowTotal12>0?number_format($rowTotal12,1).'%':'—' }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td class="px-4 py-2">Total per CPL</td>
                            @foreach($cpls as $cpl)<td class="px-2 py-2 text-center">{{ $cplTotals12[$cpl->id]>0?number_format($cplTotals12[$cpl->id],1).'%':'—' }}</td>@endforeach
                            <td class="px-2 py-2 text-center">{{ number_format(array_sum($cplTotals12),1) }}%</td>
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
