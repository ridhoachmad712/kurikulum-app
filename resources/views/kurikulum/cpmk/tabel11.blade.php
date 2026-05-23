@extends('layouts.app')
@section('title','Tabel 11 – % Bobot CPMK & Sub-CPMK')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">
    Distribusi % bobot tiap Sub-CPMK dalam CPMK. Bobot Sub-CPMK = Total Bobot CPMK ÷ Jumlah Sub-CPMK dalam CPMK tersebut.
</p>

@forelse($mataKuliahs as $mk)
@php
    $allSubs = $mk->cpmks->flatMap->subCpmks->sortBy('urutan')->values();
    $mkTotal = $mk->cpmks->sum('total_bobot');
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
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-amber-50 text-gray-600">Total</th>
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-indigo-50 text-gray-600">Tabel 7</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mk->cpmks as $cpmk)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-3 py-2 text-gray-700">
                        <span class="font-semibold text-indigo-600 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 70) }}
                    </td>
                    @foreach($allSubs as $sub)
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        @if($sub->cpmk_id === $cpmk->id && $cpmk->bobot_per_sub > 0)
                        <span class="text-blue-700 font-medium">{{ number_format($cpmk->bobot_per_sub, 2) }}%</span>
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    {{-- Total --}}
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-amber-50
                               {{ $cpmk->total_bobot > 0 ? 'text-amber-700' : 'text-gray-300' }}">
                        {{ $cpmk->total_bobot > 0 ? number_format($cpmk->total_bobot, 2).'%' : '—' }}
                    </td>
                    {{-- Tabel 7 (same value, verification column) --}}
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-indigo-50
                               {{ $cpmk->total_bobot > 0 ? 'text-indigo-700' : 'text-gray-300' }}">
                        {{ $cpmk->total_bobot > 0 ? number_format($cpmk->total_bobot, 2).'%' : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Baris total MK --}}
                <tr class="bg-indigo-900 text-white font-bold">
                    <td class="border border-indigo-800 px-3 py-2">Total</td>
                    @foreach($allSubs as $sub)
                    @php
                        $parentCpmk = $mk->cpmks->firstWhere('id', $sub->cpmk_id);
                        $val = $parentCpmk?->bobot_per_sub ?? 0;
                    @endphp
                    <td class="border border-indigo-800 px-2 py-2 text-center text-xs">
                        {{ $val > 0 ? number_format($val, 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-indigo-800 px-2 py-2 text-center bg-amber-900">
                        {{ number_format($mkTotal, 1) }}%
                    </td>
                    <td class="border border-indigo-800 px-2 py-2 text-center">
                        {{ number_format($mkTotal, 1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada data CPMK dengan Sub-CPMK</p>
</div>
@endforelse

@endsection
