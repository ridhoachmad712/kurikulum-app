@extends('layouts.app')
@section('title','Tabel 7 – Bobot Keterkaitan CPMK & CPL')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">Bobot persentase CPMK terhadap CPL yang dibebankan.</p>

@foreach($mataKuliahs as $mk)
@php
    $mkCplTotals = [];
    foreach ($cpls as $cpl) { $mkCplTotals[$cpl->id] = 0; }
@endphp
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-indigo-900 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-mono text-xs text-indigo-300">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-sm">{{ $mk->nama }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                    @foreach($cpls as $cpl)
                    <th class="px-2 py-2 text-center w-20 text-gray-600">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="px-2 py-2 text-center w-20 bg-indigo-50 text-gray-600">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($mk->cpmks as $cpmk)
                @php $rowTotal = 0; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700">
                        <span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 80) }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php
                        $cc = $cpmk->cpmkCpls->firstWhere('cpl_id', $cpl->id);
                        $bobot = $cc ? (float) $cc->bobot_persen : 0;
                        $rowTotal += $bobot;
                        $mkCplTotals[$cpl->id] += $bobot;
                    @endphp
                    <td class="px-2 py-2 text-center border-l border-gray-50">
                        @if($bobot > 0)
                            <span class="text-blue-700 font-medium">{{ number_format($bobot,2) }}%</span>
                        @else
                            <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-2 py-2 text-center font-bold bg-indigo-50
                               {{ abs($rowTotal-100)<0.1 && $rowTotal>0 ? 'text-green-600' : ($rowTotal>0 ? 'text-amber-600' : 'text-gray-300') }}">
                        {{ $rowTotal > 0 ? number_format($rowTotal,1).'%' : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Baris total per CPL --}}
                <tr class="bg-indigo-900 text-white font-bold">
                    <td class="px-4 py-2">Total per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="px-2 py-2 text-center">
                        {{ $mkCplTotals[$cpl->id] > 0 ? number_format($mkCplTotals[$cpl->id],1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="px-2 py-2 text-center">{{ number_format(array_sum($mkCplTotals),1) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endforeach

@if($mataKuliahs->isEmpty())
<div class="text-center py-12 text-gray-400">Belum ada CPMK yang diisi.</div>
@endif
@endsection
