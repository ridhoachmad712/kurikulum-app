@extends('layouts.app')
@section('title','Tabel 5 – % Bobot CPL per MK')
@section('content')

@include('kurikulum.matriks._tabs')

<div class="mb-2">
    <h2 class="text-sm font-semibold text-gray-700 mb-1">Tabel 5 — % Bobot CPL dalam Satu Mata Kuliah</h2>
    <p class="text-xs text-gray-400 mb-3">= Nilai / Total Nilai per MK × 100. Menunjukkan distribusi bobot CPL di dalam satu mata kuliah (setiap baris = 100%).</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[72vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-emerald-800 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-emerald-800 px-3 py-2 text-left w-8">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-emerald-800 px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-emerald-800 px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="sticky top-0 z-10 bg-emerald-700 px-2 py-2 text-center w-16">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                @php
                    $rowHasData = ($rowTotals[$mk->id] ?? 0) > 0;
                    $rowSum = 0;
                @endphp
                <tr class="border-b border-gray-100 {{ $rowHasData ? 'hover:bg-emerald-50/30' : '' }} group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-emerald-50/20 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-emerald-50/20 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php $val = $pctPerMk[$mk->id][$cpl->id] ?? 0; $rowSum += $val; @endphp
                    <td class="px-2 py-1.5 text-center border-r border-gray-50">
                        @if($val > 0)
                            <span class="text-emerald-700 font-medium">{{ number_format($val, 1) }}%</span>
                        @else
                            <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-2 py-1.5 text-center font-bold bg-emerald-50 text-emerald-700">
                        {{ $rowHasData ? number_format($rowSum, 1).'%' : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
