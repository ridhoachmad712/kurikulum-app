@extends('layouts.app')
@section('title','Dashboard Kelengkapan Kurikulum')
@section('content')

@php
    $tot    = $summary['total_mk'] ?: 1;
    $totSub = $summary['total_sub'] ?: 1;
@endphp

{{-- ── STAT CARDS ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-7">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Total MK</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $summary['total_mk'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">MK Lengkap</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $summary['mk_lengkap'] }}</p>
        <div class="mt-2 bg-gray-100 rounded-full h-1.5">
            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ round($summary['mk_lengkap']/$tot*100) }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-0.5">{{ round($summary['mk_lengkap']/$tot*100) }}% selesai</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">MK Belum Selesai</p>
        <p class="text-3xl font-bold text-amber-500 mt-1">{{ $summary['mk_pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Total Sub-CPMK</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $summary['total_sub'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $summary['total_asesmen'] }} asesmen terisi</p>
    </div>
</div>

{{-- ── PIPELINE ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-7">
    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Progress Pengisian (per MK)</h2>
    <div class="flex items-center gap-1">
        @php
        $steps = [
            ['label' => '1. Matriks',    'pct' => $summary['pct_matriks'],  'color' => 'bg-indigo-500'],
            ['label' => '2. CPMK',       'pct' => $summary['pct_cpmk'],     'color' => 'bg-emerald-500'],
            ['label' => '3. Sub-CPMK',   'pct' => $summary['pct_subcpmk'],  'color' => 'bg-blue-500'],
            ['label' => '4. CLO',        'pct' => $summary['pct_clo'],      'color' => 'bg-purple-500'],
            ['label' => '5. Asesmen',    'pct' => $summary['pct_asesmen'],  'color' => 'bg-amber-500'],
        ];
        @endphp
        @foreach($steps as $i => $step)
        <div class="flex-1">
            <div class="flex justify-between mb-1">
                <span class="text-xs text-gray-500">{{ $step['label'] }}</span>
                <span class="text-xs font-semibold {{ $step['pct']==100 ? 'text-green-600' : 'text-gray-400' }}">{{ $step['pct'] }}%</span>
            </div>
            <div class="bg-gray-100 rounded-full h-2">
                <div class="{{ $step['color'] }} h-2 rounded-full" style="width: {{ $step['pct'] }}%"></div>
            </div>
        </div>
        @if($i < count($steps)-1)<div class="text-gray-300 text-base mx-1">›</div>@endif
        @endforeach
    </div>
</div>

{{-- ── BREAKDOWN PER SEMESTER ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-7">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress per Semester</h2>
        <a href="{{ route('kelengkapan') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($bySemester as $smt => $data)
        <div class="rounded-lg border border-gray-100 p-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600">Smt {{ $smt }}</span>
                <span class="text-xs font-bold {{ $data['pct']==100 ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $data['pct'] }}%
                </span>
            </div>
            <div class="bg-gray-100 rounded-full h-1.5 mb-1">
                <div class="{{ $data['pct']==100 ? 'bg-green-500' : 'bg-indigo-400' }} h-1.5 rounded-full"
                     style="width: {{ $data['pct'] }}%"></div>
            </div>
            <p class="text-xs text-gray-400">{{ $data['lengkap'] }}/{{ $data['total'] }} MK</p>
        </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- ── TABEL MK BELUM SELESAI ── --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700 text-sm">Mata Kuliah Belum Selesai</h2>
            <a href="{{ route('admin.penugasan.index') }}" class="text-xs text-indigo-600 hover:underline">Kelola Penugasan →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5 text-left">MK</th>
                        <th class="px-3 py-2.5 text-center w-24">Langkah</th>
                        <th class="px-4 py-2.5 text-left">Dosen Ditugaskan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($mkPending->flatten() as $mk)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <p class="font-mono text-indigo-600">{{ $mk->kode_rps }}</p>
                            <p class="text-gray-600 truncate max-w-xs">{{ $mk->nama }}</p>
                            <p class="text-gray-400">Smt {{ $mk->semester }}</p>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full font-semibold
                                {{ $mk->score == 0 ? 'bg-gray-100 text-gray-400' : 'bg-amber-100 text-amber-700' }}">
                                {{ $mk->score }}/5
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            @forelse($mk->dosens as $d)
                            <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full mr-1 mb-1">{{ $d->name }}</span>
                            @empty
                            <span class="text-gray-300 italic">—</span>
                            @endforelse
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-green-600 font-medium">
                        🎉 Semua mata kuliah sudah lengkap!
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── RINGKASAN PER DOSEN ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700 text-sm">Ringkasan per Dosen</h2>
            <span class="text-xs text-gray-400">{{ $dosens->count() }} dosen</span>
        </div>
        <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @forelse($dosens as $d)
            <div class="px-5 py-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $d->name }}</p>
                        @if($d->nip)<p class="text-xs text-gray-400">{{ $d->nip }}</p>@endif
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs text-gray-500">{{ $d->total_mk }} MK</p>
                        <p class="text-xs {{ $d->mk_pending > 0 ? 'text-amber-600' : 'text-green-600' }} font-medium">
                            {{ $d->mk_pending > 0 ? $d->mk_pending.' belum selesai' : 'Semua selesai' }}
                        </p>
                    </div>
                </div>
                @if($d->total_mk > 0)
                <div class="mt-2 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full"
                         style="width: {{ $d->total_mk > 0 ? round($d->mk_lengkap/$d->total_mk*100) : 0 }}%"></div>
                </div>
                @else
                <p class="text-xs text-gray-300 mt-1 italic">Belum ada penugasan</p>
                @endif
            </div>
            @empty
            <x-empty-state icon="users" title="Belum ada dosen terdaftar"/>
            @endforelse
        </div>
    </div>
</div>

@endsection
