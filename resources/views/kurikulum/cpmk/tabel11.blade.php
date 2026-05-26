@extends('layouts.app')
@section('title','Tabel 11 – % Bobot CPMK & Sub-CPMK')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">
    Distribusi % bobot tiap Sub-CPMK dalam CPMK. Bobot Sub-CPMK = Total Bobot CPMK ÷ Jumlah Sub-CPMK dalam CPMK tersebut.
</p>

@forelse($mataKuliahs as $mk)
@php
    $allSubs         = $mk->cpmks->flatMap->subCpmks->sortBy('urutan')->values();
    $mkTotal         = $mk->cpmks->sum('total_bobot');
    $subsWithAsesmen = $mk->cpmks->flatMap->subCpmks->filter(fn($s) => $s->asesmen)->sortBy('urutan')->values();
    $bobotPerSubMap  = [];
    foreach ($mk->cpmks as $cpmk) {
        foreach ($cpmk->subCpmks as $s) {
            $bobotPerSubMap[$s->id] = (float) $cpmk->bobot_per_sub;
        }
    }
@endphp

<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header MK --}}
    <div class="bg-gray-800 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-mono text-xs text-gray-400">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-sm">{{ $mk->nama }}</span>
        <span class="ml-auto text-xs text-gray-400">Smt {{ $mk->semester }}</span>
    </div>

    {{-- Tabel Bobot per Sub-CPMK --}}
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
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-gray-50 text-gray-600">Tabel 7</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mk->cpmks as $cpmk)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-3 py-2 text-gray-700">
                        <span class="font-semibold text-gray-700 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 70) }}
                    </td>
                    @foreach($allSubs as $sub)
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        @if($sub->cpmk_id === $cpmk->id)
                            @php $bobotSub = $sub->bobot !== null ? (float)$sub->bobot : (float)$cpmk->bobot_per_sub; @endphp
                            @if($bobotSub > 0)
                            <span class="text-blue-700 font-medium">{{ number_format($bobotSub, 2) }}%</span>
                            @else
                            <span class="text-gray-200">—</span>
                            @endif
                        @else
                        <span class="text-gray-200">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-amber-50
                               {{ $cpmk->total_bobot > 0 ? 'text-amber-700' : 'text-gray-300' }}">
                        {{ $cpmk->total_bobot > 0 ? number_format($cpmk->total_bobot, 2).'%' : '—' }}
                    </td>
                    <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-gray-50
                               {{ $cpmk->total_bobot > 0 ? 'text-gray-800' : 'text-gray-300' }}">
                        {{ $cpmk->total_bobot > 0 ? number_format($cpmk->total_bobot, 2).'%' : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Baris total MK --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2">Total</td>
                    @foreach($allSubs as $sub)
                    @php
                        $parentCpmk = $mk->cpmks->firstWhere('id', $sub->cpmk_id);
                        $val = $sub->bobot !== null ? (float)$sub->bobot : (float)($parentCpmk?->bobot_per_sub ?? 0);
                    @endphp
                    <td class="border border-gray-700 px-2 py-2 text-center text-xs">
                        {{ $val > 0 ? number_format($val, 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center bg-amber-900">
                        {{ number_format($mkTotal, 1) }}%
                    </td>
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ number_format($mkTotal, 1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Seksi Bobot Asesmen --}}
    @if($subsWithAsesmen->isNotEmpty())
    <div class="border-t-2 border-gray-200">
        <div class="px-4 py-2 bg-gray-50 border-b border-gray-200 flex items-center gap-2">
            <span class="w-1.5 h-4 rounded-full bg-gray-500 inline-block shrink-0"></span>
            <p class="text-xs font-semibold text-gray-800">Bobot Asesmen per Sub-CPMK</p>
            <span class="ml-auto text-[10px] text-gray-400">Jumlah Asesmen = Bobot Teoritis</span>
        </div>
        <div class="overflow-x-auto">
            <table class="text-xs w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-200 px-3 py-2 text-left min-w-[160px] text-gray-600">Sub-CPMK</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">Tugas</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">Kuis</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">Kasus/Proyek</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">UTS</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">UAS</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-emerald-50 text-gray-600">Jumlah</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-24 bg-gray-50 text-gray-600">Bobot Teoritis</th>
                        <th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subsWithAsesmen as $sub)
                    @php
                        $a        = $sub->asesmen;
                        $total    = $a->tugas + $a->quis + $a->kasus_proyek + $a->uts + $a->uas;
                        $teoritis = $bobotPerSubMap[$sub->id] ?? 0;
                        $selaras  = $teoritis > 0 && abs($total - $teoritis) <= 0.5;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-200 px-3 py-2 font-semibold text-gray-700">{{ $sub->kode }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center text-gray-700">{{ $a->tugas > 0 ? $a->tugas : '—' }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center text-gray-700">{{ $a->quis > 0 ? $a->quis : '—' }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center text-gray-700">{{ $a->kasus_proyek > 0 ? $a->kasus_proyek : '—' }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center text-gray-700">{{ $a->uts > 0 ? $a->uts : '—' }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center text-gray-700">{{ $a->uas > 0 ? $a->uas : '—' }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-emerald-50 text-emerald-700">{{ $total }}</td>
                        <td class="border border-gray-200 px-2 py-2 text-center font-bold bg-gray-50 text-gray-800">{{ number_format($teoritis, 2) }}%</td>
                        <td class="border border-gray-200 px-2 py-2 text-center">
                            @if($selaras)
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">✓ Selaras</span>
                            @else
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">
                                    {{ $total > $teoritis ? '+' : '' }}{{ number_format($total - $teoritis, 1) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="border-t border-gray-100 px-4 py-3 bg-gray-50">
        <p class="text-xs text-gray-400 italic">Bobot asesmen belum diisi —
            <a href="{{ route('kurikulum.asesmen.index') }}" class="text-indigo-500 hover:underline">Input sekarang</a>
        </p>
    </div>
    @endif

</div>{{-- end card --}}
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada data CPMK dengan Sub-CPMK</p>
</div>
@endforelse

@endsection
