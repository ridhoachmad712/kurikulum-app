@extends('layouts.app')
@section('title','Log Error Aplikasi')
@section('content')

@php
function fmtLogBytes($b) {
    if (!$b) return '0 B';
    $u = ['B','KB','MB','GB']; $i = floor(log($b, 1024));
    return round($b / pow(1024, $i), 1) . ' ' . $u[$i];
}
$levelMeta = [
    'emergency' => 'bg-red-100 text-red-700',
    'alert'     => 'bg-red-100 text-red-700',
    'critical'  => 'bg-red-100 text-red-700',
    'error'     => 'bg-red-100 text-red-700',
    'warning'   => 'bg-amber-100 text-amber-700',
    'notice'    => 'bg-blue-100 text-blue-700',
    'info'      => 'bg-sky-100 text-sky-700',
    'debug'     => 'bg-gray-100 text-gray-600',
];
@endphp

<x-page-header title="Log Error Aplikasi" subtitle="Pesan error & peringatan dari laravel.log{{ $size ? ' · '.fmtLogBytes($size) : '' }}"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin">
    <x-slot name="actions">
        @if($exists && count($entries))
        <form method="POST" action="{{ route('super-admin.error-logs.clear') }}" onsubmit="return confirm('Kosongkan seluruh isi log error?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-secondary">Kosongkan Log</button>
        </form>
        @endif
    </x-slot>
</x-page-header>

{{-- Filter level --}}
<div class="flex items-center gap-1.5 mb-5 flex-wrap">
    <a href="{{ route('super-admin.error-logs.index') }}"
       class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !$level ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</a>
    @foreach(['error'=>'Error','warning'=>'Warning','info'=>'Info','debug'=>'Debug'] as $key => $lbl)
    <a href="{{ route('super-admin.error-logs.index', ['level'=>$key]) }}"
       class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $level === $key ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $lbl }}</a>
    @endforeach
</div>

@if(!$exists)
<div class="bg-white rounded-xl border border-gray-100 py-14 text-center text-gray-400">
    <p class="text-sm">File log tidak ditemukan.</p>
</div>
@elseif(empty($entries))
<div class="bg-white rounded-xl border border-gray-100 py-14 text-center text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
    <p class="text-sm font-medium text-gray-500">Tidak ada entri log{{ $level ? ' untuk level '.$level : '' }}.</p>
    <p class="text-xs mt-1">Bagus — berarti tidak ada error yang tercatat. 🎉</p>
</div>
@else
<div class="space-y-2" x-data>
    @foreach($entries as $e)
    @php $cls = $levelMeta[strtolower($e['level'])] ?? 'bg-gray-100 text-gray-600'; @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: false }">
        <button type="button" @click="open = !open" class="w-full flex items-start gap-3 px-4 py-3 text-left hover:bg-gray-50 transition">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase shrink-0 {{ $cls }}">{{ $e['level'] }}</span>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-gray-700 font-mono break-words {{ $e['trace'] ? '' : '' }}" :class="open ? '' : 'line-clamp-2'">{{ $e['message'] }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $e['time'] }} · {{ $e['env'] }}</p>
            </div>
            @if($e['trace'])
            <svg class="w-4 h-4 text-gray-300 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            @endif
        </button>
        @if($e['trace'])
        <div x-show="open" x-cloak>
            <pre class="px-4 py-3 bg-gray-900 text-gray-300 text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap border-t border-gray-100">{{ Str::limit($e['trace'], 4000) }}</pre>
        </div>
        @endif
    </div>
    @endforeach
</div>
<p class="text-[11px] text-gray-400 mt-4 text-center">Menampilkan maks 200 entri terbaru dari 500 KB terakhir file log.</p>
@endif

@endsection
