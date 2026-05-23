@extends('layouts.app')
@section('title','Tabel 9 – Matriks CPMK & Sub-CPMK')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">Keterkaitan CPMK dengan Sub-CPMK yang dibebankan pada setiap Mata Kuliah.</p>

@forelse($mataKuliahs as $mk)
@php
    $allSubs = $mk->cpmks->flatMap->subCpmks->sortBy('urutan')->values();
    $subCount = $allSubs->count();
@endphp
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                    @foreach($allSubs as $sub)
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">
                        {{ $sub->kode }}
                    </th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-indigo-50 text-gray-600">Jml</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($mk->cpmks as $cpmk)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-3 py-2 text-gray-700">
                        <span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 70) }}
                    </td>
                    @foreach($allSubs as $sub)
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        @if($sub->cpmk_id === $cpmk->id)
                        <span class="text-green-600 font-bold text-sm">V</span>
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-indigo-50 text-indigo-700">
                        {{ $cpmk->subCpmks->count() }}
                    </td>
                </tr>
                @endforeach

                {{-- Baris total --}}
                <tr class="bg-indigo-900 text-white font-bold text-xs">
                    <td class="border border-indigo-800 px-3 py-2">Total Sub-CPMK</td>
                    @foreach($allSubs as $sub)
                    <td class="border border-indigo-800 px-2 py-2 text-center">1</td>
                    @endforeach
                    <td class="border border-indigo-800 px-2 py-2 text-center">{{ $subCount }}</td>
                </tr>
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
