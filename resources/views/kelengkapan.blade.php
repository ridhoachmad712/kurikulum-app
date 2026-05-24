@extends('layouts.app')
@section('title','Status Kelengkapan Mata Kuliah')
@section('content')

<x-page-header title="Status Kelengkapan" subtitle="Progres pengisian data kurikulum per mata kuliah"/>

{{-- Filter + Legend --}}
<div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
    <form method="GET" class="flex items-center gap-2">
        <select name="semester" onchange="this.form.submit()" class="form-input py-1.5 text-sm w-auto">
            <option value="">Semua Semester</option>
            @for($s=1;$s<=8;$s++)
            <option value="{{ $s }}" {{ $semester == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
            @endfor
        </select>
    </form>
    <div class="flex items-center gap-3 text-xs text-gray-500">
        <span class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-green-500 inline-block"></span> Selesai</span>
        <span class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-amber-400 inline-block"></span> Sebagian</span>
        <span class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-gray-200 inline-block"></span> Belum</span>
    </div>
</div>

@forelse($grouped as $smt => $mks)
@php
    $total   = $mks->count();
    $lengkap = $mks->where('score', 5)->count();
    $pct     = $total ? round($lengkap / $total * 100) : 0;
@endphp
<div class="mb-8">
    {{-- Semester Header --}}
    <div class="flex items-center gap-3 mb-3">
        <h2 class="font-bold text-gray-700">Semester {{ $smt }}</h2>
        <div class="flex-1 bg-gray-100 rounded-full h-2">
            <div class="h-2 rounded-full {{ $pct==100 ? 'bg-green-500' : 'bg-indigo-400' }}"
                 style="width: {{ $pct }}%"></div>
        </div>
        <span class="text-xs font-semibold {{ $pct==100 ? 'text-green-600' : 'text-gray-500' }}">
            {{ $lengkap }}/{{ $total }} ({{ $pct }}%)
        </span>
    </div>

    {{-- MK Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-2.5 text-left">Kode / Nama MK</th>
                    <th class="px-3 py-2.5 text-left">Dosen Pengampu</th>
                    <th class="px-3 py-2.5 text-center w-20" title="Matriks MK–CPL">Matriks</th>
                    <th class="px-3 py-2.5 text-center w-16" title="CPMK terisi">CPMK</th>
                    <th class="px-3 py-2.5 text-center w-20" title="Sub-CPMK terisi">Sub-CPMK</th>
                    <th class="px-3 py-2.5 text-center w-16" title="CLO tertinggi semua terisi">CLO</th>
                    <th class="px-3 py-2.5 text-center w-20" title="Bobot asesmen semua terisi">Asesmen</th>
                    <th class="px-3 py-2.5 text-center w-24">Skor</th>
                    <th class="px-3 py-2.5 text-center w-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($mks as $mk)
                @php
                    $rowBg = match(true) {
                        $mk->score === 5 => 'bg-green-50',
                        $mk->score === 0 => '',
                        default          => 'bg-amber-50/40',
                    };
                @endphp
                <tr class="hover:bg-gray-50 {{ $rowBg }}">
                    <td class="px-4 py-2.5">
                        <p class="font-mono text-gray-700 font-semibold">{{ $mk->kode_rps }}</p>
                        <p class="text-gray-700 font-medium">{{ $mk->nama }}</p>
                        @if($mk->konsentrasi)<p class="text-gray-400 text-xs">{{ $mk->konsentrasi }}</p>@endif
                    </td>
                    <td class="px-3 py-2.5">
                        @forelse($mk->dosens as $d)
                        <span class="inline-block bg-gray-50 text-gray-800 px-1.5 py-0.5 rounded text-xs mb-0.5">
                            {{ $d->name }}
                        </span>
                        @empty
                        <span class="text-gray-300 italic text-xs">Belum ditugaskan</span>
                        @endforelse
                    </td>

                    @php
                        $steps = [
                            ['key'=>'matriks',  'href'=> route('kurikulum.matriks.input')],
                            ['key'=>'cpmk',     'href'=> route('kurikulum.cpmk.index')],
                            ['key'=>'subcpmk',  'href'=> route('kurikulum.sub-cpmk.index')],
                            ['key'=>'clo',      'href'=> route('kurikulum.sub-cpmk.index')],
                            ['key'=>'asesmen',  'href'=> route('kurikulum.asesmen.show', $mk)],
                        ];
                    @endphp
                    @foreach($steps as $step)
                    <td class="px-3 py-2.5 text-center">
                        @if($mk->checks[$step['key']])
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600 font-bold">✓</span>
                        @else
                        <a href="{{ $step['href'] }}"
                           class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-400 hover:bg-amber-100 hover:text-amber-600 transition"
                           title="Isi sekarang">✗</a>
                        @endif
                    </td>
                    @endforeach

                    <td class="px-3 py-2.5 text-center">
                        @php
                            $scoreColor = match($mk->score) {
                                5 => 'bg-green-100 text-green-700',
                                0 => 'bg-gray-100 text-gray-400',
                                default => 'bg-amber-100 text-amber-700',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-bold {{ $scoreColor }}">
                            {{ $mk->score }}<span class="font-normal text-gray-400">/5</span>
                        </span>
                    </td>
                    <td class="px-3 py-2.5 text-center">
                        <a href="{{ route('master.mata-kuliah.show', $mk) }}"
                           class="text-gray-700 hover:underline text-xs">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<x-empty-state icon="inbox" title="Belum ada mata kuliah aktif"
    subtitle="Tambahkan mata kuliah di menu Master untuk melihat status kelengkapan kurikulum."/>
@endforelse

@endsection
