@extends('layouts.app')
@section('title','Tabel 12 – % Matriks Sub-CPMK & CPL')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">
    Persentase bobot <strong>Sub-CPMK</strong> terhadap CPL — diturunkan dari bobot CPMK induk dibagi rata
    ke seluruh Sub-CPMK-nya. Total per kolom CPL sama dengan Tabel 7.
</p>

@forelse($mataKuliahs as $mk)
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
                    <th class="border border-gray-200 px-3 py-2 text-left w-24 text-gray-600">CPMK</th>
                    <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600">Sub-CPMK</th>
                    @foreach($cpls as $cpl)
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600"
                        title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-gray-50 text-gray-600">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mk->t12_rows as $row)
                <tr class="hover:bg-gray-50">
                    {{-- Kode CPMK hanya ditampilkan di baris pertama tiap kelompok --}}
                    @if($row['cpmk_first'])
                    <td class="border border-gray-200 px-3 py-2 align-top font-semibold text-gray-700 bg-gray-50/60"
                        rowspan="{{ $row['cpmk_span'] }}">{{ $row['cpmk_kode'] }}</td>
                    @endif
                    <td class="border border-gray-200 px-3 py-2 text-gray-700">
                        <span class="font-semibold text-teal-700 mr-1">{{ $row['sub_kode'] }}</span>
                        {{ Str::limit($row['sub_desc'], 60) }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php $bobot = $row['cells'][$cpl->id] ?? 0; @endphp
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        @if($bobot > 0)
                        <span class="text-blue-700 font-medium">{{ number_format($bobot, 2) }}%</span>
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-gray-50
                               {{ $row['total'] > 0 ? 'text-gray-800' : 'text-gray-300' }}">
                        {{ $row['total'] > 0 ? number_format($row['total'], 2).'%' : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Total per CPL (sama dengan Tabel 7) --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2" colspan="2">Total per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ ($mk->t12_colTotals[$cpl->id] ?? 0) > 0 ? number_format($mk->t12_colTotals[$cpl->id], 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ number_format($mk->t12_grand, 1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada data Sub-CPMK</p>
    <p class="text-sm mt-1">Isi CPMK, bobot CPMK–CPL, dan Sub-CPMK terlebih dahulu.</p>
</div>
@endforelse

@endsection
