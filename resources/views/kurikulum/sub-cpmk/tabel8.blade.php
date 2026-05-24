@extends('layouts.app')
@section('title','Tabel 8 – Rekapitulasi Sub-CPMK')
@section('content')

@include('kurikulum._kurikulum-tabs')

@forelse($mataKuliahs as $mk)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
        <span class="font-mono text-xs text-gray-700 mr-2">{{ $mk->kode_rps }}</span>
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
                    @php
                        $clo = $sc->subCpmkClos->where('is_tertinggi', true)->first()?->cloTaxonomi;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        @if($loop->first)
                        <td class="border border-gray-200 px-3 py-2 font-semibold text-gray-700 align-top"
                            rowspan="{{ $cpmk->subCpmks->count() }}">
                            {{ $cpmk->kode }}
                        </td>
                        @endif
                        <td class="border border-gray-200 px-3 py-2 font-medium text-teal-700">{{ $sc->kode }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $sc->deskripsi }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-center">
                            @if($clo)
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded font-mono font-semibold">{{ $clo->kode }}</span>
                            @else
                            <span class="text-gray-300">–</span>
                            @endif
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
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada Sub-CPMK</p>
    <p class="text-sm mt-1">Isi Sub-CPMK terlebih dahulu.</p>
</div>
@endforelse

@endsection
