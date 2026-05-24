@extends('layouts.app')
@section('title','Tabel 12 – % Matriks Sub-CPMK & CPL')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">Persentase bobot CPMK terhadap CPL, beserta total kontribusi per CPL.</p>

@forelse($mataKuliahs as $mk)
@php
    $cplTotals = [];
    foreach ($cpls as $cpl) { $cplTotals[$cpl->id] = 0; }
    $mkGrandTotal = 0;
@endphp
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-800 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-mono text-xs text-gray-400">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-sm">{{ $mk->nama }}</span>
        <span class="ml-auto text-xs text-gray-400">Smt {{ $mk->semester }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-3 py-2 text-left min-w-[240px] text-gray-600">CPMK</th>
                    @foreach($cpls as $cpl)
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600"
                        title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-gray-50 text-gray-600">Tabel 11</th>
                </tr>
            </thead>
            <tbody>
                {{-- Baris total atas (per CPL dari MK) --}}
                @php
                    $mkCplSums = [];
                    foreach ($cpls as $cpl) {
                        $mkCplSums[$cpl->id] = $mk->cpmks->sum(function($cpmk) use ($cpl) {
                            $cc = $cpmk->cpmkCpls->firstWhere('cpl_id', $cpl->id);
                            return $cc ? (float)$cc->bobot_persen : 0;
                        });
                    }
                @endphp
                <tr class="bg-gray-100 font-semibold text-gray-600">
                    <td class="border border-gray-200 px-3 py-1.5 text-xs italic">Total % per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="border border-gray-200 px-2 py-1.5 text-center text-xs">
                        {{ $mkCplSums[$cpl->id] > 0 ? number_format($mkCplSums[$cpl->id], 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-1.5 text-center bg-gray-50 text-xs">
                        {{ number_format(array_sum($mkCplSums), 1) }}%
                    </td>
                </tr>

                @foreach($mk->cpmks as $cpmk)
                @php
                    $rowTotal = 0;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-3 py-2 text-gray-700">
                        <span class="font-semibold text-gray-700 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 75) }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php
                        $cc    = $cpmk->cpmkCpls->firstWhere('cpl_id', $cpl->id);
                        $bobot = $cc ? (float)$cc->bobot_persen : 0;
                        $rowTotal          += $bobot;
                        $cplTotals[$cpl->id] += $bobot;
                        $mkGrandTotal      += $bobot;
                    @endphp
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        @if($bobot > 0)
                        <span class="text-blue-700 font-medium">{{ number_format($bobot, 2) }}%</span>
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    {{-- Tabel 11 = total bobot CPMK --}}
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-gray-50
                               {{ abs($rowTotal - 100) < 0.1 && $rowTotal > 0 ? 'text-green-600' : ($rowTotal > 0 ? 'text-amber-600' : 'text-gray-300') }}">
                        {{ $rowTotal > 0 ? number_format($rowTotal, 2).'%' : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Total row per CPL --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2">Total per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ $cplTotals[$cpl->id] > 0 ? number_format($cplTotals[$cpl->id], 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ number_format(array_sum($cplTotals), 1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada data bobot CPMK–CPL</p>
    <p class="text-sm mt-1">Isi bobot CPMK terlebih dahulu di menu CPMK.</p>
</div>
@endforelse

@endsection
