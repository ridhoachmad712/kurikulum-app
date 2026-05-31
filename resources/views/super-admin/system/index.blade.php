@extends('layouts.app')
@section('title','Info Sistem')
@section('content')

@php
function fmtBytes($bytes) {
    if (!$bytes) return '0 B';
    $units = ['B','KB','MB','GB','TB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 1) . ' ' . $units[$i];
}
@endphp

<x-page-header title="Info Sistem" subtitle="Versi, penyimpanan, basis data, dan statistik aplikasi"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin"/>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Aplikasi & Server --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-blue-600"></span>
            <p class="text-xs font-semibold text-gray-600">Aplikasi & Server</p>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50">
                @foreach([
                    'Versi Laravel' => $app['laravel'],
                    'Versi PHP'     => $app['php'],
                    'Web Server'    => $app['server'],
                    'Sistem Operasi'=> $app['os'],
                    'Timezone'      => $app['timezone'],
                    'Environment'   => $app['env'],
                    'Debug Mode'    => $app['debug'],
                ] as $label => $val)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-2.5 text-gray-500 text-xs">{{ $label }}</td>
                    <td class="px-5 py-2.5 text-right font-medium text-gray-800 text-xs font-mono">{{ $val }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Database & Disk --}}
    <div class="space-y-6">
        {{-- Database --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-emerald-500"></span>
                <p class="text-xs font-semibold text-gray-600">Basis Data</p>
            </div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50"><td class="px-5 py-2.5 text-gray-500 text-xs">Driver</td><td class="px-5 py-2.5 text-right font-medium text-gray-800 text-xs font-mono">{{ $db['driver'] }}</td></tr>
                    <tr class="hover:bg-gray-50"><td class="px-5 py-2.5 text-gray-500 text-xs">Nama</td><td class="px-5 py-2.5 text-right font-medium text-gray-800 text-xs font-mono">{{ $db['name'] }}</td></tr>
                    <tr class="hover:bg-gray-50"><td class="px-5 py-2.5 text-gray-500 text-xs">Ukuran</td><td class="px-5 py-2.5 text-right font-bold text-emerald-700 text-xs">{{ fmtBytes($db['size']) }}</td></tr>
                    <tr class="hover:bg-gray-50"><td class="px-5 py-2.5 text-gray-500 text-xs">Backup Terakhir</td><td class="px-5 py-2.5 text-right font-medium text-gray-800 text-xs">{{ $lastBackup ? \Illuminate\Support\Carbon::parse($lastBackup)->format('d M Y, H:i') : '— belum pernah —' }}</td></tr>
                </tbody>
            </table>
        </div>

        {{-- Disk --}}
        @if($disk)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-amber-500"></span>
                <p class="text-xs font-semibold text-gray-600">Penyimpanan Disk</p>
            </div>
            <div class="px-5 py-4">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-gray-500">Terpakai {{ fmtBytes($disk['used']) }} dari {{ fmtBytes($disk['total']) }}</span>
                    <span class="font-bold {{ $disk['pct'] >= 90 ? 'text-red-600' : ($disk['pct'] >= 70 ? 'text-amber-600' : 'text-gray-700') }}">{{ $disk['pct'] }}%</span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $disk['pct'] >= 90 ? 'bg-red-500' : ($disk['pct'] >= 70 ? 'bg-amber-400' : 'bg-emerald-500') }}"
                         style="width: {{ $disk['pct'] }}%"></div>
                </div>
                <p class="text-[11px] text-gray-400 mt-2">Sisa ruang: {{ fmtBytes($disk['free']) }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Bersihkan Cache --}}
<div class="mt-6">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pemeliharaan Cache</p>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-500 leading-relaxed mb-4">
            Bersihkan cache aplikasi tanpa perlu SSH — berguna setelah <span class="font-mono">git pull</span> di server agar perubahan kode &amp; tampilan langsung diterapkan.
        </p>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('super-admin.cache.clear') }}">
                @csrf
                <input type="hidden" name="type" value="all">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                    Bersihkan Semua
                </button>
            </form>
            @foreach(['config'=>'Konfigurasi','view'=>'Tampilan','route'=>'Rute','cache'=>'Aplikasi'] as $type => $label)
            <form method="POST" action="{{ route('super-admin.cache.clear') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="btn-secondary">{{ $label }}</button>
            </form>
            @endforeach
        </div>
    </div>
</div>

{{-- Statistik record --}}
<div class="mt-6">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Jumlah Record</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        @foreach($counts as $label => $count)
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3.5 text-center">
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ number_format($count) }}</p>
            <p class="text-[10px] text-gray-400 uppercase tracking-wider mt-1.5">{{ $label }}</p>
        </div>
        @endforeach
    </div>
</div>

@endsection
