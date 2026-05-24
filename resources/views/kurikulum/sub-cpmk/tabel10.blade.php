@extends('layouts.app')
@section('title','Tabel 10 – Keterkaitan Sub-CPMK & CPL')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">Keterkaitan Sub-CPMK dengan CPL yang dibebankan (diwarisi dari CPMK induk).</p>

@forelse($mataKuliahs as $mk)
@php
    // Bangun set: cpmk_id → [cpl_id, ...]
    $cpmkCplMap = [];
    foreach ($mk->cpmks as $cpmk) {
        $cpmkCplMap[$cpmk->id] = $cpmk->cpmkCpls
            ->where('has_relation', true)
            ->pluck('cpl_id')
            ->toArray();
    }
    $cplTotal = array_fill_keys($cpls->pluck('id')->toArray(), 0);
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
                    <th class="border border-gray-200 px-3 py-2 text-left w-28 text-gray-600">Sub-CPMK</th>
                    <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600">Deskripsi</th>
                    @foreach($cpls as $cpl)
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600"
                        title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-gray-50 text-gray-600">Jml</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mk->cpmks as $cpmk)
                    @foreach($cpmk->subCpmks as $sub)
                    @php
                        $relatedCplIds = $cpmkCplMap[$cpmk->id] ?? [];
                        $subTotal = 0;
                        foreach ($relatedCplIds as $cplId) {
                            $cplTotal[$cplId] = ($cplTotal[$cplId] ?? 0) + 1;
                            $subTotal++;
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        @if($loop->first)
                        <td class="border border-gray-200 px-3 py-2 font-semibold text-gray-700 align-middle text-center"
                            rowspan="{{ $cpmk->subCpmks->count() }}">
                            {{ $cpmk->kode }}
                        </td>
                        @endif
                        <td class="border border-gray-200 px-3 py-2 text-teal-700 font-medium">{{ $sub->kode }}</td>
                        @foreach($cpls as $cpl)
                        <td class="border border-gray-200 px-2 py-2 text-center">
                            @if(in_array($cpl->id, $relatedCplIds))
                            <span class="text-green-600 font-bold text-sm">V</span>
                            @else
                            <span class="text-gray-200">—</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-gray-50 text-gray-800">
                            {{ $subTotal }}
                        </td>
                    </tr>
                    @endforeach
                @endforeach

                {{-- Baris total per CPL --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2" colspan="2">Total per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ $cplTotal[$cpl->id] > 0 ? $cplTotal[$cpl->id] : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ array_sum($cplTotal) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada Sub-CPMK</p>
</div>
@endforelse

@endsection
