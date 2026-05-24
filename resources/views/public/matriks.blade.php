@extends('layouts.public')
@section('title', 'Matriks MK–CPL')

@section('content')

{{-- Page header --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-3">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-600 font-medium">Matriks MK–CPL</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">Matriks Keterkaitan MK–CPL</h1>
        <p class="text-sm text-gray-500 mt-1">
            Tingkat kontribusi setiap mata kuliah terhadap Capaian Pembelajaran Lulusan (CPL)
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Legend --}}
    <div class="flex flex-wrap items-center gap-4 mb-6 text-xs text-gray-500">
        <span class="font-semibold text-gray-700">Keterangan:</span>
        @foreach(['T' => ['Tinggi', 'bg-blue-700 text-white'], 'S' => ['Sedang', 'bg-indigo-200 text-blue-800'], 'R' => ['Rendah', 'bg-blue-50 text-blue-300']] as $sym => $info)
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded {{ $info[1] }} font-bold text-xs">{{ $sym }}</span>
            {{ $info[0] }}
        </span>
        @endforeach
        <span class="flex items-center gap-1.5">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-gray-300 text-xs">—</span>
            Tidak terkait
        </span>
    </div>

    {{-- Semester tabs --}}
    <div x-data="{ tab: 'all' }" class="mb-6">
        <div class="flex flex-wrap gap-2 mb-6">
            <button @click="tab='all'"
                    :class="tab==='all' ? 'bg-blue-700 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-indigo-300'"
                    class="px-4 py-2 text-sm font-medium rounded-xl transition">
                Semua
            </button>
            @foreach($mataKuliahsBySmt as $smt => $mks)
            <button @click="tab='{{ $smt }}'"
                    :class="tab==='{{ $smt }}' ? 'bg-blue-700 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-indigo-300'"
                    class="px-4 py-2 text-sm font-medium rounded-xl transition">
                Semester {{ $smt }}
                <span class="ml-1 opacity-60 text-xs">({{ $mks->count() }})</span>
            </button>
            @endforeach
        </div>

        {{-- Matriks table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-auto">
            <table class="text-xs w-full border-collapse">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="sticky left-0 bg-blue-900 px-4 py-3 text-left min-w-[60px] border-r border-blue-800">Smt</th>
                        <th class="sticky left-[60px] bg-blue-900 px-4 py-3 text-left min-w-[180px] border-r border-blue-800">Mata Kuliah</th>
                        @foreach($cpls as $cpl)
                        <th class="px-2 py-3 text-center w-16 font-semibold border-r border-blue-800/40 last:border-r-0" title="{{ $cpl->deskripsi }}">
                            {{ $cpl->kode }}
                        </th>
                        @endforeach
                        <th class="px-3 py-3 text-center w-16 bg-blue-800 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mataKuliahsBySmt as $smt => $smtMks)
                    @foreach($smtMks as $i => $mk)
                    @php $rowTotal = $rowTotals[$mk->id] ?? 0; @endphp
                    <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors"
                        x-show="tab==='all' || tab==='{{ $smt }}'" x-cloak>
                        {{-- Semester label (rowspan-like: show only on first row of semester) --}}
                        <td class="sticky left-0 bg-white px-3 py-2.5 border-r border-gray-100 text-center font-semibold text-indigo-500">
                            @if($i === 0)
                            <span class="inline-block w-6 h-6 rounded-full bg-blue-100 text-blue-800 font-bold text-xs leading-6">{{ $smt }}</span>
                            @endif
                        </td>
                        <td class="sticky left-[60px] bg-white px-4 py-2.5 border-r border-gray-100">
                            <a href="{{ route('public.mata-kuliah.show', $mk) }}"
                               class="group flex flex-col">
                                <span class="font-mono text-blue-700 font-semibold group-hover:underline">{{ $mk->kode_rps }}</span>
                                <span class="text-gray-500 group-hover:text-gray-700 line-clamp-1 transition">{{ $mk->nama }}</span>
                            </a>
                        </td>
                        @foreach($cpls as $cpl)
                        @php $nilai = $nilaiMatrix[$mk->id][$cpl->id] ?? 0; @endphp
                        <td class="px-2 py-2.5 text-center border-r border-gray-50 last:border-r-0">
                            @if($nilai === 5)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-700 text-white font-bold text-xs">T</span>
                            @elseif($nilai === 3)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-200 text-blue-800 font-bold text-xs">S</span>
                            @elseif($nilai === 1)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-300 font-bold text-xs">R</span>
                            @else
                            <span class="text-gray-200">—</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-3 py-2.5 text-center font-bold {{ $rowTotal > 0 ? 'text-blue-800' : 'text-gray-300' }}">
                            {{ $rowTotal > 0 ? $rowTotal : '—' }}
                        </td>
                    </tr>
                    @endforeach
                    @endforeach

                    {{-- Column totals --}}
                    <tr class="bg-blue-50 border-t-2 border-blue-100">
                        <td class="sticky left-0 bg-blue-50 px-3 py-2.5 border-r border-blue-100"></td>
                        <td class="sticky left-[60px] bg-blue-50 px-4 py-2.5 font-bold text-gray-700 border-r border-blue-100">Total per CPL</td>
                        @foreach($cpls as $cpl)
                        @php $ct = $colTotals[$cpl->id] ?? 0; @endphp
                        <td class="px-2 py-2.5 text-center font-bold {{ $ct > 0 ? 'text-blue-800' : 'text-gray-300' }}">
                            {{ $ct > 0 ? $ct : '—' }}
                        </td>
                        @endforeach
                        <td class="px-3 py-2.5 text-center font-bold text-blue-800">
                            {{ array_sum($colTotals) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- CPL detail strip --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($cpls as $cpl)
            <div class="bg-white rounded-xl border border-gray-100 p-3 flex items-start gap-2.5">
                <span class="shrink-0 w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xs">{{ $cpl->kode }}</span>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">{{ $cpl->deskripsi }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
