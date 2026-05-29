@extends('layouts.app')
@section('title','Tabel 1 – Level Keterkaitan MK & CPL')
@section('content')

@include('kurikulum.matriks._tabs')

{{-- Info dosen: read-only, arahkan ke menu edit --}}
@hasrole('dosen')
<div class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-xl px-4 py-2.5 mb-4 text-xs text-amber-700">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>
        Halaman ini menampilkan data level CPL secara <strong>read-only</strong>.
        Untuk mengubah isian, gunakan menu
        <a href="{{ route('kurikulum.matriks.input') }}" class="underline font-semibold hover:text-amber-900">Level CPL Mata Kuliah</a>.
    </span>
</div>
@endhasrole

{{-- Legend --}}
<div class="flex items-center gap-3 mb-4 text-xs flex-wrap">
    <span class="font-medium text-gray-600">Level keterkaitan:</span>
    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 font-medium">Tinggi</span>
    <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 font-medium">Sedang</span>
    <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">Rendah</span>
    <span class="text-gray-400">— = Tidak terkait</span>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[70vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-gray-800 px-3 py-2 text-left w-8 font-normal">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-gray-800 px-3 py-2 text-left min-w-[220px] font-semibold">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-gray-800 px-2 py-2 text-center w-24">
                        <span class="font-bold">{{ $cpl->kode }}</span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                <tr class="border-b border-gray-100 hover:bg-gray-50/30 group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-gray-50/30 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-gray-50/30 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-gray-700 mr-1">{{ $mk->kode_rps }}</span>
                        <span class="text-gray-800">{{ $mk->nama }}</span>
                        <span class="text-gray-400 ml-1">({{ $mk->sks }} SKS)</span>
                    </td>
                    @foreach($cpls as $cpl)
                    @php $level = $matriksInput[$mk->id][$cpl->id] ?? ''; @endphp
                    <td class="px-1 py-1.5 text-center border-r border-gray-50">
                        @if($level === 'Tinggi')
                            <span class="inline-block px-2 py-0.5 rounded bg-red-100 text-red-700 font-medium">Tinggi</span>
                        @elseif($level === 'Sedang')
                            <span class="inline-block px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 font-medium">Sedang</span>
                        @elseif($level === 'Rendah')
                            <span class="inline-block px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">Rendah</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
