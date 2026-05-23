@extends('layouts.app')
@section('title','Tabel 2 – Matriks Keterkaitan MK & CPL')
@section('content')

@include('kurikulum.matriks._tabs')

<p class="text-xs text-gray-500 mb-4">
    Nilai <strong>1</strong> = ada keterkaitan (Rendah/Sedang/Tinggi), <strong>0</strong> = tidak terkait.
    Diturunkan otomatis dari Input Level.
</p>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[72vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-indigo-900 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-indigo-900 px-3 py-2 text-left w-8">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-indigo-900 px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-indigo-900 px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="sticky top-0 z-10 bg-indigo-800 px-2 py-2 text-center w-16">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                @php $rowTotal = 0; @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50 group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-gray-50 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-gray-50 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-indigo-600 mr-1 text-xs">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php $val = $binary[$mk->id][$cpl->id] ?? 0; $rowTotal += $val; @endphp
                    <td class="px-2 py-1.5 text-center border-r border-gray-50">
                        @if($val)
                            <span class="font-bold text-indigo-700">1</span>
                        @else
                            <span class="text-gray-300">0</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-2 py-1.5 text-center font-bold bg-indigo-50 text-indigo-700">{{ $rowTotal }}</td>
                </tr>
                @endforeach

                {{-- Baris total --}}
                <tr class="bg-indigo-900 text-white font-bold text-xs">
                    <td class="sticky left-0 z-10 bg-indigo-900 px-3 py-2 text-center" colspan="2">Jumlah MK yang mendukung CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="px-2 py-2 text-center">{{ $binaryColTotals[$cpl->id] ?? 0 }}</td>
                    @endforeach
                    <td class="px-2 py-2 text-center">{{ array_sum($binaryColTotals) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
