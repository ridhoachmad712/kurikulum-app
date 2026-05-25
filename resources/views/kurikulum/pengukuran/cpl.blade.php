@extends('layouts.app')
@section('title','Pengukuran CPL')
@section('content')

{{-- Filter bar --}}
<div class="flex items-center gap-2 mb-6 flex-wrap">
    @hasanyrole('admin|kaprodi')
    <form method="GET" class="flex items-center gap-2">
        @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <select name="semester"
                onchange="this.form.submit()"
                class="form-input w-36 py-2 text-sm">
            <option value="">Semua Semester</option>
            @foreach(range(1,8) as $s)
            <option value="{{ $s }}" {{ $semester == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
            @endforeach
        </select>
        @if($semester)
        <a href="{{ route('kurikulum.pengukuran.cpl') }}" class="text-xs text-gray-400 hover:text-gray-600">Reset</a>
        @endif
    </form>
    @endhasanyrole
    <x-search-input placeholder="Cari kode atau nama MK..."/>
    <p class="ml-auto text-xs text-gray-400">{{ $mataKuliahs->count() }} Mata Kuliah</p>
</div>

@if($search ?? null)
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ $search }}"</strong> — {{ $mataKuliahs->count() }} ditemukan</p>
@endif

{{-- Info scope dosen --}}
@hasrole('dosen')
<div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 mb-4 text-xs text-indigo-700">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda.</span>
</div>
@endhasrole

<div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 text-xs text-blue-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
    </svg>
    <span>
        <strong>Pengukuran CPL</strong> — Rencana distribusi bobot CPMK terhadap setiap CPL (bukan nilai mahasiswa).
        Nilai sel = bobot % CPMK terhadap CPL. Bar rekap menunjukkan proporsi relatif antar CPL; bar terpanjang = CPL dengan bobot tertinggi.
    </span>
</div>

@if($cpls->isEmpty())
{{-- CPL program studi belum ada sama sekali --}}
<div class="text-center py-16 text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    <p class="font-medium">CPL belum tersedia</p>
    <p class="text-sm mt-1">Pastikan CPL program studi sudah diisi oleh Kaprodi.</p>
</div>
@elseif($mataKuliahs->isEmpty() && ($search || $semester))
{{-- Filter/search aktif tapi tidak ada hasil --}}
<div class="text-center py-16 text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <p class="font-medium">Tidak ada hasil</p>
    @if($search)
    <p class="text-sm mt-1">Tidak ada mata kuliah yang cocok dengan <strong class="text-gray-600">"{{ $search }}"</strong>{{ $semester ? ' di Semester '.$semester : '' }}.</p>
    @else
    <p class="text-sm mt-1">Tidak ada mata kuliah dengan data bobot CPL di Semester {{ $semester }}.</p>
    @endif
    <a href="{{ route('kurikulum.pengukuran.cpl') }}" class="inline-block mt-3 text-xs text-indigo-600 hover:underline">Reset filter</a>
</div>
@elseif($mataKuliahs->isEmpty())
{{-- Tidak ada MK yang sudah punya bobot CPMK–CPL --}}
<div class="text-center py-16 text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    <p class="font-medium">Data belum tersedia</p>
    <p class="text-sm mt-1">Pastikan CPMK dan bobot CPMK×CPL sudah diisi terlebih dahulu.</p>
</div>
@else

{{-- Ringkasan total per CPL (lintas semua MK) --}}
@php
    $maxCplScore = count($cplTotals) > 0 ? max(array_values($cplTotals)) : 0;
    $avgScore    = $cpls->count() > 0 && $grandTotal > 0 ? $grandTotal / $cpls->count() : 0;
@endphp
<div class="mb-6">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Rekap Total Bobot CPL (semua MK)</p>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-{{ min($cpls->count(), 7) }} gap-3">
        @foreach($cpls as $cpl)
        @php
            $score    = $cplTotals[$cpl->id] ?? 0;
            $colorBar  = $score >= $avgScore * 0.8 ? 'bg-green-500' : ($score >= $avgScore * 0.4 ? 'bg-amber-400' : 'bg-red-400');
            $colorVal  = $score >= $avgScore * 0.8 ? 'text-green-700' : ($score >= $avgScore * 0.4 ? 'text-amber-700' : 'text-red-600');
            $pct       = $maxCplScore > 0 ? round($score / $maxCplScore * 100) : 0;
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3.5">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ $cpl->kode }}</p>
            <p class="text-xl font-bold {{ $colorVal }}">
                {{ number_format($score, 1) }}<span class="text-xs font-normal text-gray-400">%</span>
            </p>
            <div class="mt-1.5 bg-gray-100 rounded-full h-1 overflow-hidden">
                <div class="{{ $colorBar }} h-full rounded-full" style="width: {{ $pct }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Per Mata Kuliah --}}
@foreach($mataKuliahs as $mk)
@php
    // CPMK-CPL map untuk MK ini
    $cpmkCplMap = [];
    foreach ($mk->cpmks as $cpmk) {
        foreach ($cpmk->cpmkCpls as $cc) {
            if ($cc->has_relation && $cc->bobot_persen > 0) {
                $cpmkCplMap[$cpmk->id][$cc->cpl_id] = (float) $cc->bobot_persen;
            }
        }
    }

    // Filter hanya CPMK yang punya minimal 1 relasi CPL
    $activeCpmks = $mk->cpmks->filter(fn($c) => isset($cpmkCplMap[$c->id]));

    // Total per CPL dari MK ini
    $mkCplTotals = [];
    foreach ($cpls as $cpl) {
        $mkCplTotals[$cpl->id] = $cplMkScore[$cpl->id][$mk->id] ?? 0;
    }
    $mkGrandTotal = array_sum($mkCplTotals);
@endphp

@if($activeCpmks->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">

    {{-- Header MK --}}
    <div class="bg-gray-800 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-mono text-xs text-gray-400">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-sm">{{ $mk->nama }}</span>
        <span class="ml-auto text-xs text-gray-400">Smt {{ $mk->semester }} · {{ $mk->sks }} SKS</span>
    </div>

    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600">CPMK</th>
                    @foreach($cpls as $cpl)
                    <th class="border border-gray-200 px-2 py-2 text-center w-16 text-gray-600">
                        <span class="font-semibold">{{ $cpl->kode }}</span>
                    </th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-amber-50 text-amber-700 font-semibold">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeCpmks as $cpmk)
                @php
                    $rowTotal = array_sum($cpmkCplMap[$cpmk->id] ?? []);
                @endphp
                <tr class="hover:bg-gray-50/50">
                    <td class="border border-gray-200 px-3 py-2">
                        <span class="font-semibold text-gray-700 mr-1">{{ $cpmk->kode }}</span>
                        <span class="text-gray-500">{{ Str::limit($cpmk->deskripsi, 65) }}</span>
                    </td>
                    @foreach($cpls as $cpl)
                    @php
                        $val = $cpmkCplMap[$cpmk->id][$cpl->id] ?? 0;
                    @endphp
                    <td class="border border-gray-200 px-2 py-2 text-center {{ $val > 0 ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-200' }}">
                        {{ $val > 0 ? number_format($val, 1) : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-2 text-center bg-amber-50 font-bold text-amber-700">
                        {{ $rowTotal > 0 ? number_format($rowTotal, 1) : '—' }}
                    </td>
                </tr>
                @endforeach

                {{-- Total per CPL dari MK ini --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2 text-xs">Total MK ini</td>
                    @foreach($cpls as $cpl)
                    @php $colVal = $mkCplTotals[$cpl->id] ?? 0; @endphp
                    <td class="border border-gray-700 px-2 py-2 text-center text-xs {{ $colVal > 0 ? '' : 'text-gray-500' }}">
                        {{ $colVal > 0 ? number_format($colVal, 1) : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center bg-amber-900 text-amber-300 text-xs">
                        {{ number_format($mkGrandTotal, 1) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif
@endforeach

@endif

@endsection
