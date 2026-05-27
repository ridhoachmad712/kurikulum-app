@extends('layouts.public')
@section('title', 'Bahan Kajian (BK)')

@section('content')

{{-- ─── PAGE HEADER ────────────────────────────────────────────── --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
            <span class="text-gray-600 font-medium">Bahan Kajian</span>
        </nav>
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-1">Kurikulum</p>
        <h1 class="text-xl font-bold text-gray-900">Bahan Kajian (BK)</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $prodi->nama }} &middot; {{ $bahanKajians->count() }} Bahan Kajian</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TABEL 6.2 — Deskripsi Bahan Kajian                           --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-start gap-2">
                <div class="w-0.5 self-stretch bg-blue-500 rounded-full shrink-0 mt-0.5"></div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Tabel 6.2 — Deskripsi Bahan Kajian</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Daftar bahan kajian beserta nama dan deskripsi masing-masing</p>
                </div>
            </div>
        </div>

        @if($bahanKajians->isEmpty())
        <div class="px-6 py-10 text-center text-gray-400 text-sm">
            Belum ada data bahan kajian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap w-20">Kode</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap w-56">Nama Bahan Kajian</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bahanKajians as $bk)
                    <tr class="hover:bg-gray-50 transition-colors align-top">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-100 text-blue-800 font-bold text-xs">
                                {{ $bk->kode }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-800 align-top">
                            {{ $bk->nama }}
                        </td>
                        <td class="px-5 py-4 text-gray-600 leading-relaxed align-top">
                            {{ $bk->deskripsi }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TABEL 6.3 — Matrix CPL × BK                                  --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-start gap-2">
                <div class="w-0.5 self-stretch bg-emerald-500 rounded-full shrink-0 mt-0.5"></div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Tabel 6.3 — Keterkaitan CPL dengan Bahan Kajian</h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Tanda <span class="font-semibold text-emerald-600">&#10003;</span> = CPL terkait dengan bahan kajian tersebut &nbsp;&middot;&nbsp;
                        <span class="text-gray-300">&mdash;</span> = tidak terkait
                    </p>
                </div>
            </div>
        </div>

        @if($cpls->isEmpty() || $bahanKajians->isEmpty())
        <div class="px-6 py-10 text-center text-gray-400 text-sm">
            Belum ada data CPL atau Bahan Kajian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap sticky left-0 bg-gray-50 z-10 min-w-[80px]">CPL</th>
                        @foreach($bahanKajians as $bk)
                        <th class="px-3 py-3 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap min-w-[56px]">
                            {{ $bk->kode }}
                        </th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap bg-blue-50/60 min-w-[48px]">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cpls as $cpl)
                    @php
                        $rowTotal = 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap sticky left-0 bg-white hover:bg-gray-50 z-10">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-800 font-bold text-xs">
                                {{ $cpl->kode }}
                            </span>
                        </td>
                        @foreach($bahanKajians as $bk)
                        @php
                            $has = $cplBkMap[$cpl->id][$bk->id] ?? false;
                            if ($has) $rowTotal++;
                        @endphp
                        <td class="px-3 py-3 text-center">
                            @if($has)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-sm">&#10003;</span>
                            @else
                            <span class="text-gray-200 text-base">&mdash;</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-3 py-3 text-center bg-blue-50/60">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                {{ $rowTotal > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-50 text-gray-300' }}
                                font-bold text-xs">
                                {{ $rowTotal }}
                            </span>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Column totals row --}}
                    <tr class="bg-blue-50/60 border-t-2 border-blue-100">
                        <td class="px-5 py-3 whitespace-nowrap sticky left-0 bg-blue-50/80 z-10">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</span>
                        </td>
                        @foreach($bahanKajians as $bk)
                        @php
                            $colTotal = collect($cpls)->filter(fn($cpl) => $cplBkMap[$cpl->id][$bk->id] ?? false)->count();
                        @endphp
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                {{ $colTotal > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-50 text-gray-300' }}
                                font-bold text-xs">
                                {{ $colTotal }}
                            </span>
                        </td>
                        @endforeach
                        <td class="px-3 py-3 text-center">
                            {{-- grand total --}}
                            @php
                                $grandTotal = 0;
                                foreach($cpls as $cpl) {
                                    foreach($bahanKajians as $bk) {
                                        if ($cplBkMap[$cpl->id][$bk->id] ?? false) $grandTotal++;
                                    }
                                }
                            @endphp
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-200 text-blue-900 font-bold text-xs">
                                {{ $grandTotal }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- TABEL 6.4 — Matrix MK × BK                                   --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-start gap-2">
                <div class="w-0.5 self-stretch bg-violet-500 rounded-full shrink-0 mt-0.5"></div>
                <div>
                    <h2 class="font-bold text-gray-900 text-sm">Tabel 6.4 — Keterkaitan Mata Kuliah dengan Bahan Kajian</h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Tanda <span class="font-semibold text-emerald-600">&#10003;</span> = Mata Kuliah memuat bahan kajian tersebut &nbsp;&middot;&nbsp;
                        <span class="text-gray-300">&mdash;</span> = tidak terkait &nbsp;&middot;&nbsp;
                        Scroll horizontal untuk melihat seluruh kolom BK
                    </p>
                </div>
            </div>
        </div>

        @if($mataKuliahs->isEmpty() || $bahanKajians->isEmpty())
        <div class="px-6 py-10 text-center text-gray-400 text-sm">
            Belum ada data Mata Kuliah atau Bahan Kajian.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap sticky left-0 bg-gray-50 z-10 min-w-[280px]">Mata Kuliah</th>
                        @foreach($bahanKajians as $bk)
                        <th class="px-3 py-3 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider whitespace-nowrap min-w-[56px]" title="{{ $bk->nama }}">
                            {{ $bk->kode }}
                        </th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-semibold text-gray-500 text-xs uppercase tracking-wider whitespace-nowrap bg-violet-50/60 min-w-[48px]">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($mataKuliahs as $mk)
                    @php
                        $rowTotal = 0;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-2.5 sticky left-0 bg-white hover:bg-gray-50 z-10">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center shrink-0 w-6 h-6 rounded-md bg-gray-100 text-gray-500 font-semibold text-[10px]">
                                    {{ $mk->semester }}
                                </span>
                                <span class="text-gray-800 font-medium text-xs leading-snug">{{ $mk->nama }}</span>
                            </div>
                        </td>
                        @foreach($bahanKajians as $bk)
                        @php
                            $has = $mkBkMap[$mk->id][$bk->id] ?? false;
                            if ($has) $rowTotal++;
                        @endphp
                        <td class="px-3 py-2.5 text-center">
                            @if($has)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-sm">&#10003;</span>
                            @else
                            <span class="text-gray-200 text-base">&mdash;</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-3 py-2.5 text-center bg-violet-50/60">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                {{ $rowTotal > 0 ? 'bg-violet-100 text-violet-700' : 'bg-gray-50 text-gray-300' }}
                                font-bold text-xs">
                                {{ $rowTotal }}
                            </span>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Column totals row --}}
                    <tr class="bg-violet-50/60 border-t-2 border-violet-100">
                        <td class="px-5 py-3 sticky left-0 bg-violet-50/80 z-10">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</span>
                        </td>
                        @foreach($bahanKajians as $bk)
                        @php
                            $colTotal = collect($mataKuliahs)->filter(fn($mk) => $mkBkMap[$mk->id][$bk->id] ?? false)->count();
                        @endphp
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                {{ $colTotal > 0 ? 'bg-violet-100 text-violet-700' : 'bg-gray-50 text-gray-300' }}
                                font-bold text-xs">
                                {{ $colTotal }}
                            </span>
                        </td>
                        @endforeach
                        <td class="px-3 py-3 text-center">
                            @php
                                $mkGrandTotal = 0;
                                foreach($mataKuliahs as $mk) {
                                    foreach($bahanKajians as $bk) {
                                        if ($mkBkMap[$mk->id][$bk->id] ?? false) $mkGrandTotal++;
                                    }
                                }
                            @endphp
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-violet-200 text-violet-800 font-bold text-xs">
                                {{ $mkGrandTotal }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@endsection
