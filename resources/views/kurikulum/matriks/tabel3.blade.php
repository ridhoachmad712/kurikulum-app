@extends('layouts.app')
@section('title','Tabel 3 – Matriks Nilai Level Keterkaitan')
@section('content')

@include('kurikulum.matriks._tabs')

<p class="text-xs text-gray-500 mb-4">
    Konversi level: <strong>Tinggi = 5</strong>, <strong>Sedang = 3</strong>, <strong>Rendah = 1</strong>, Tidak terkait = 0.
    Grand Total = {{ number_format($grandTotal) }} (dipakai sebagai pembagi Tabel 4).
</p>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[72vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-gray-800 px-3 py-2 text-left w-8">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-gray-800 px-3 py-2 text-left min-w-[220px]">Mata Kuliah</th>
                    <th class="sticky top-0 z-10 bg-gray-800 px-2 py-2 text-center w-10">SKS</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-gray-800 px-2 py-2 text-center w-16">{{ $cpl->kode }}</th>
                    @endforeach
                    <th class="sticky top-0 z-10 bg-gray-700 px-2 py-2 text-center w-16">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                <tr class="border-b border-gray-100 hover:bg-gray-50 group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-gray-50 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-gray-50 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-gray-700 mr-1">{{ $mk->kode_rps }}</span>{{ $mk->nama }}
                    </td>
                    <td class="px-2 py-1.5 text-center text-gray-500">{{ $mk->sks }}</td>
                    @foreach($cpls as $cpl)
                    @php $val = $nilaiMatrix[$mk->id][$cpl->id] ?? 0; @endphp
                    <td class="px-2 py-1.5 text-center border-r border-gray-50">
                        @if($val > 0)
                            <span class="{{ $val===5 ? 'text-red-600 font-bold' : ($val===3 ? 'text-yellow-600 font-semibold' : 'text-green-600') }}">
                                {{ $val }}
                            </span>
                        @else
                            <span class="text-gray-200">0</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-2 py-1.5 text-center font-bold bg-gray-50 text-gray-800">{{ $rowTotals[$mk->id] ?? 0 }}</td>
                </tr>
                @endforeach

                {{-- Baris total kolom --}}
                <tr class="bg-gray-800 text-white font-bold text-xs">
                    <td class="sticky left-0 z-10 bg-gray-800 px-3 py-2 text-center" colspan="3">Total per CPL</td>
                    @foreach($cpls as $cpl)
                    <td class="px-2 py-2 text-center">{{ $colTotals[$cpl->id] ?? 0 }}</td>
                    @endforeach
                    <td class="px-2 py-2 text-center">{{ $grandTotal }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
