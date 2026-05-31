@extends('layouts.app')
@section('title','Super Admin')
@section('content')

<x-page-header title="Super Admin" subtitle="Pusat kontrol sistem — manajemen akun, prodi, audit, dan pengaturan"/>

{{-- Statistik ringkas --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-7">
    @foreach([
        ['val' => $stats['total_user'],  'label' => 'Total Pengguna'],
        ['val' => $stats['total_admin'], 'label' => 'Admin'],
        ['val' => $stats['total_dosen'], 'label' => 'Dosen'],
        ['val' => $stats['total_prodi'], 'label' => 'Program Studi'],
        ['val' => $stats['total_mk'],    'label' => 'Mata Kuliah'],
    ] as $s)
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3.5">
        <p class="text-2xl font-bold text-gray-800 leading-none">{{ $s['val'] }}</p>
        <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Menu fitur Super Admin --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    @php
    $features = [
        [
            'title' => 'Kelola Akun & Role',
            'desc'  => 'Atur role setiap pengguna — termasuk menjadikan admin atau super admin.',
            'icon'  => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
            'tint'  => 'blue',
            'route' => 'super-admin.users.index',
        ],
        [
            'title' => 'Log Aktivitas',
            'desc'  => 'Riwayat audit — siapa mengubah apa dan kapan.',
            'icon'  => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z',
            'tint'  => 'amber',
            'route' => 'super-admin.logs.index',
        ],
        [
            'title' => 'Backup Database',
            'desc'  => 'Export seluruh data aplikasi sebagai file .sql.',
            'icon'  => 'M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z',
            'tint'  => 'violet',
            'route' => 'super-admin.backup.index',
        ],
        [
            'title' => 'Pengaturan Aplikasi',
            'desc'  => 'Identitas app, footer, mode pemeliharaan, & pengingat istirahat.',
            'icon'  => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
            'tint'  => 'rose',
            'route' => 'super-admin.settings.index',
        ],
        [
            'title' => 'Info Sistem',
            'desc'  => 'Versi PHP/Laravel, ukuran DB, penyimpanan disk, & statistik.',
            'icon'  => 'M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z',
            'tint'  => 'emerald',
            'route' => 'super-admin.system.index',
        ],
    ];
    $tintMap = [
        'blue'    => 'bg-blue-50 text-blue-700',
        'emerald' => 'bg-emerald-50 text-emerald-700',
        'amber'   => 'bg-amber-50 text-amber-700',
        'violet'  => 'bg-violet-50 text-violet-700',
        'rose'    => 'bg-rose-50 text-rose-700',
    ];
    @endphp

    @foreach($features as $f)
    @php $tc = $tintMap[$f['tint']]; $href = $f['route'] ? route($f['route']) : null; @endphp
    <{{ $href ? 'a' : 'div' }} @if($href) href="{{ $href }}" @endif
        class="group bg-white border border-gray-200 rounded-2xl p-5 flex items-start gap-4 {{ $href ? 'hover:shadow-md hover:border-gray-300 transition-all' : 'opacity-75' }}">
        <div class="w-11 h-11 rounded-xl {{ $tc }} flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/>
            </svg>
        </div>
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-bold text-gray-800">{{ $f['title'] }}</h3>
                @unless($href)
                <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-400 text-[9px] font-semibold uppercase tracking-wide">Segera</span>
                @endunless
            </div>
            <p class="text-xs text-gray-500 leading-relaxed mt-1">{{ $f['desc'] }}</p>
        </div>
    </{{ $href ? 'a' : 'div' }}>
    @endforeach
</div>

@endsection
