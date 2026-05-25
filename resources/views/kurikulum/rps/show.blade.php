@extends('layouts.app')
@section('title','Detail RPS')
@section('content')

@php
    $user     = auth()->user();
    $isOwner  = $user->id === $rps->dosen_id;
    $isAdmin  = $user->hasAnyRole('admin|kaprodi');
    $isDraft  = $rps->status === 'Draft';
    $isReview = $rps->status === 'Review';
    $isFinal  = $rps->status === 'Final';
    $statusColor = match($rps->status) {
        'Final'  => 'bg-green-100 text-green-700 border-green-200',
        'Review' => 'bg-amber-100 text-amber-700 border-amber-200',
        default  => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp

{{-- ── BACK + HEADER ── --}}
<div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
    <div class="flex items-center gap-3">
        <a href="{{ route('kurikulum.rps.index') }}"
           class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-lg font-bold text-gray-800">RPS – {{ $rps->mataKuliah->nama }}</h1>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusColor }}">
                    {{ $rps->status }}
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ $rps->mataKuliah->kode_rps }} &bull; {{ $rps->tahun_ajaran }} &bull; Semester {{ $rps->semester }}
            </p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2 flex-wrap">
        @if($isDraft && ($isOwner || $isAdmin))
        <a href="{{ route('kurikulum.rps.edit', $rps) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        @endif

        @if($isDraft && $isOwner && $user->isDosen())
        <form method="POST" action="{{ route('kurikulum.rps.submit', $rps) }}">
            @csrf
            <button type="submit" onclick="return confirm('Ajukan RPS ini untuk direview oleh Kaprodi?')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Ajukan untuk Review
            </button>
        </form>
        @endif

        @if($isReview && $isAdmin)
        <form method="POST" action="{{ route('kurikulum.rps.approve', $rps) }}">
            @csrf
            <button type="submit" onclick="return confirm('Setujui RPS ini? Status akan berubah ke Final.')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Setujui (Final)
            </button>
        </form>
        <form method="POST" action="{{ route('kurikulum.rps.reject', $rps) }}">
            @csrf
            <button type="submit" onclick="return confirm('Kembalikan RPS ke Draft untuk direvisi?')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium rounded-xl transition-colors border border-red-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Kembalikan ke Draft
            </button>
        </form>
        @endif

        @if($isDraft && ($isOwner || $isAdmin))
        <form method="POST" action="{{ route('kurikulum.rps.destroy', $rps) }}">
            @csrf @method('DELETE')
            <button type="submit" onclick="return confirm('Hapus RPS ini secara permanen? Data tidak bisa dipulihkan.')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-xl transition-colors border border-red-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- ── INFO CARD ── --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi RPS</h2>
        <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Kode MK</dt>
                <dd class="font-mono text-gray-700 font-semibold">{{ $rps->mataKuliah->kode_rps }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Nama Mata Kuliah</dt>
                <dd class="text-gray-800 font-medium">{{ $rps->mataKuliah->nama }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Tahun Ajaran</dt>
                <dd class="text-gray-700">{{ $rps->tahun_ajaran }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Semester Pelaksanaan</dt>
                <dd class="text-gray-700">{{ $rps->semester }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Dosen Pengampu</dt>
                <dd class="text-gray-700">{{ $rps->dosen->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">SKS</dt>
                <dd class="text-gray-700">{{ $rps->mataKuliah->sks ?? '—' }}</dd>
            </div>
            @if($rps->submitted_at)
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Diajukan pada</dt>
                <dd class="text-gray-700">{{ $rps->submitted_at->format('d M Y, H:i') }}</dd>
            </div>
            @endif
            @if($rps->approved_at)
            <div>
                <dt class="text-gray-400 text-xs mb-0.5">Disetujui pada</dt>
                <dd class="text-green-600 font-medium">{{ $rps->approved_at->format('d M Y, H:i') }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- ── TIMELINE CARD ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Status Workflow</h2>
        @php
            $steps = [
                ['label' => 'Draft',    'desc' => 'Pengisian awal',         'done' => true],
                ['label' => 'Review',   'desc' => 'Diajukan ke Kaprodi',    'done' => !$isDraft],
                ['label' => 'Final',    'desc' => 'Disetujui & diterbitkan','done' => $isFinal],
            ];
        @endphp
        <ol class="relative border-l border-gray-200 ml-2 space-y-4">
            @foreach($steps as $step)
            <li class="ml-4">
                <span class="absolute -left-1.5 flex items-center justify-center w-3 h-3 rounded-full
                    {{ $step['done'] ? 'bg-gray-700' : 'bg-gray-200' }}">
                </span>
                <p class="text-sm font-semibold {{ $step['done'] ? 'text-gray-800' : 'text-gray-400' }}">
                    {{ $step['label'] }}
                    @if($rps->status === $step['label'])
                    <span class="ml-1 text-xs font-normal text-amber-600">(sekarang)</span>
                    @endif
                </p>
                <p class="text-xs text-gray-400">{{ $step['desc'] }}</p>
            </li>
            @endforeach
        </ol>

        @if($isReview)
        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
            <strong>Menunggu persetujuan</strong> Kaprodi / Admin.
        </div>
        @elseif($isFinal)
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-xs text-green-700">
            <strong>RPS telah disetujui</strong> dan berstatus Final.
        </div>
        @endif
    </div>
</div>

{{-- ── CPMK & Sub-CPMK OVERVIEW ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="font-semibold text-gray-700 text-sm">CPMK &amp; Sub-CPMK Terkait</h2>
        <a href="{{ route('kurikulum.cpmk.show', $rps->mataKuliah) }}"
           class="text-xs text-gray-700 hover:underline">Kelola CPMK →</a>
    </div>

    @if($rps->mataKuliah->cpmks->isEmpty())
    <div class="px-5 py-8 text-center text-gray-400 text-sm">
        <p>Belum ada CPMK untuk mata kuliah ini.</p>
        <a href="{{ route('kurikulum.cpmk.show', $rps->mataKuliah) }}"
           class="mt-2 inline-block text-gray-700 hover:underline text-xs">Tambah CPMK →</a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-5 py-2.5 text-left">Kode CPMK</th>
                    <th class="px-4 py-2.5 text-left">Deskripsi</th>
                    <th class="px-4 py-2.5 text-left">Sub-CPMK</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($rps->mataKuliah->cpmks as $cpmk)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-5 py-2.5 font-mono text-gray-700 font-semibold whitespace-nowrap">
                        {{ $cpmk->kode }}
                    </td>
                    <td class="px-4 py-2.5 text-gray-700 max-w-sm">{{ $cpmk->deskripsi }}</td>
                    <td class="px-4 py-2.5">
                        @if($cpmk->subCpmks->isEmpty())
                            <span class="text-gray-300 italic">—</span>
                        @else
                        <div class="flex flex-wrap gap-1">
                            @foreach($cpmk->subCpmks as $sub)
                            <span class="inline-block bg-gray-50 text-gray-800 px-2 py-0.5 rounded font-mono">
                                {{ $sub->kode }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
