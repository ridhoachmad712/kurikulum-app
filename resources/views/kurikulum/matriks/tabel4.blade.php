@extends('layouts.app')
@section('title','Tabel 4 – % Bobot CPL Global')
@section('content')

@include('kurikulum.matriks._tabs')

<div class="mb-2">
    <h2 class="text-sm font-semibold text-gray-700 mb-1">Tabel 4 — % Bobot CPL dengan Mata Kuliah (Global)</h2>
    <p class="text-xs text-gray-400 mb-3">= Nilai / Grand Total ({{ number_format($grandTotal) }}) × 100. Menunjukkan kontribusi setiap MK terhadap CPL secara keseluruhan program studi.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[72vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-indigo-900 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-indigo-900 px-3 py-2 text-left w-8">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-indigo-900 px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-indigo-900 px-2 py-2 text-center w-20">{{ $cpl->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                @php $hasAny = collect($pctGlobal[$mk->id] ?? [])->sum() > 0; @endphp
                <tr class="border-b border-gray-100 {{ $hasAny ? 'hover:bg-blue-50/30' : '' }} group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-blue-50/20 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-blue-50/20 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php $val = $pctGlobal[$mk->id][$cpl->id] ?? 0; @endphp
                    <td class="px-2 py-1.5 text-center border-r border-gray-50">
                        @if($val > 0)
                            <span class="text-blue-700 font-medium">{{ number_format($val, 2) }}%</span>
                        @else
                            <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
                <tr class="bg-indigo-900 text-white font-bold text-xs">
                    <td class="sticky left-0 z-10 bg-indigo-900 px-3 py-2" colspan="2">Total % per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="px-2 py-2 text-center">{{ number_format($pctGlobalColTotals[$cpl->id] ?? 0, 1) }}%</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
