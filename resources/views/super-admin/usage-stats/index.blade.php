@extends('layouts.app')
@section('title','Statistik Penggunaan')
@section('content')

<x-page-header title="Statistik Penggunaan" subtitle="Aktivitas pengguna berdasarkan log — 30 hari terakhir"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin"/>

{{-- Ringkasan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-7">
    @foreach([
        ['val'=>$summary['login'],        'label'=>'Total Login',     'color'=>'text-blue-700'],
        ['val'=>$summary['unique_users'], 'label'=>'Pengguna Aktif',  'color'=>'text-emerald-700'],
        ['val'=>$summary['failed'],       'label'=>'Login Gagal',     'color'=>'text-red-600'],
        ['val'=>$summary['changes'],      'label'=>'Perubahan Data',  'color'=>'text-amber-700'],
    ] as $s)
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3.5">
        <p class="text-2xl font-bold {{ $s['color'] }} leading-none">{{ number_format($s['val']) }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Tren login 14 hari --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm font-bold text-gray-800 mb-4">Tren Login (14 Hari)</p>
        @php $maxTrend = max(1, max($trend)); @endphp
        <div class="flex items-end gap-1.5 h-40">
            @foreach($trend as $day => $count)
            <div class="flex-1 flex flex-col items-center justify-end h-full group">
                <span class="text-[9px] text-gray-400 mb-1">{{ $count ?: '' }}</span>
                <div class="w-full bg-blue-500 hover:bg-blue-600 rounded-t transition-all" style="height: {{ round($count / $maxTrend * 100) }}%; min-height: {{ $count > 0 ? '4px' : '2px' }};" title="{{ $day }}: {{ $count }} login"></div>
                <span class="text-[8px] text-gray-300 mt-1">{{ \Illuminate\Support\Carbon::parse($day)->format('d/m') }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Jam sibuk --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm font-bold text-gray-800 mb-4">Distribusi Jam Login (24 Jam)</p>
        @php $maxHour = max(1, max($byHour)); @endphp
        <div class="flex items-end gap-0.5 h-40">
            @foreach($byHour as $hour => $count)
            <div class="flex-1 flex flex-col items-center justify-end h-full">
                <div class="w-full bg-emerald-500 hover:bg-emerald-600 rounded-t transition-all" style="height: {{ round($count / $maxHour * 100) }}%; min-height: {{ $count > 0 ? '3px' : '1px' }};" title="Jam {{ $hour }}:00 — {{ $count }} login"></div>
                @if($hour % 6 === 0)<span class="text-[8px] text-gray-300 mt-1">{{ $hour }}</span>@else<span class="text-[8px] text-transparent mt-1">.</span>@endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pengguna paling aktif --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm font-bold text-gray-800 mb-4">Pengguna Paling Aktif (login)</p>
        @if($topUsers->isEmpty())
        <p class="text-xs text-gray-400 text-center py-8">Belum ada data login.</p>
        @else
        @php $maxUser = max(1, $topUsers->max('total')); @endphp
        <div class="space-y-2.5">
            @foreach($topUsers as $u)
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 w-40 truncate shrink-0">{{ $u->user_name }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-violet-500 h-full rounded-full" style="width: {{ round($u->total / $maxUser * 100) }}%"></div>
                </div>
                <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ $u->total }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Distribusi aksi --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm font-bold text-gray-800 mb-4">Distribusi Aktivitas (30 Hari)</p>
        @if($byAction->isEmpty())
        <p class="text-xs text-gray-400 text-center py-8">Belum ada aktivitas tercatat.</p>
        @else
        @php
        $maxAction = max(1, $byAction->max('total'));
        $actionLabels = ['login'=>'Login','logout'=>'Logout','login_failed'=>'Login Gagal','created'=>'Tambah','updated'=>'Ubah','deleted'=>'Hapus','password_reset'=>'Reset Pass','impersonate'=>'Masuk Sbg'];
        @endphp
        <div class="space-y-2.5">
            @foreach($byAction as $a)
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-600 w-28 shrink-0">{{ $actionLabels[$a->action] ?? $a->action }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-sky-500 h-full rounded-full" style="width: {{ round($a->total / $maxAction * 100) }}%"></div>
                </div>
                <span class="text-xs font-semibold text-gray-700 w-10 text-right">{{ $a->total }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
