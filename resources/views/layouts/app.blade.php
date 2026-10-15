<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/3logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @auth
    @php
    $themeMap = [
        'blue'    => ['#2563EB','#1D4ED8'],
        'indigo'  => ['#4F46E5','#4338CA'],
        'violet'  => ['#7C3AED','#6D28D9'],
        'emerald' => ['#059669','#047857'],
        'teal'    => ['#0D9488','#0F766E'],
        'rose'    => ['#E11D48','#BE123C'],
        'orange'  => ['#EA580C','#C2410C'],
        'pink'    => ['#DB2777','#BE185D'],
        'yellow'  => ['#CA8A04','#A16207'],
        'slate'   => ['#475569','#334155'],
    ];
    $tc = $themeMap[auth()->user()->theme_color ?? 'blue'] ?? $themeMap['blue'];
    @endphp
    <style id="theme-style">
        .nav-item-active,.logo-icon,.user-avatar,.stepper-active{background-color:{{ $tc[0] }}!important}
        .btn-primary{background-color:{{ $tc[0] }}!important}
        .btn-primary:hover{background-color:{{ $tc[1] }}!important}
        .stepper-active:hover{background-color:{{ $tc[1] }}!important}
        .user-avatar:hover{background-color:{{ $tc[1] }}!important}
    </style>

    {{-- ── TEMA TEKNIS (Data-Dense Compact) — khusus Super Admin ── --}}
    @if(auth()->user()->hasRole('super_admin'))
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style id="tech-style">
        .tech-mode { --mono: 'JetBrains Mono', ui-monospace, 'Cascadia Code', Consolas, monospace; }

        /* Semua font jadi monospace */
        .tech-mode, .tech-mode * { font-family: var(--mono) !important; }

        /* Sudut lebih tajam */
        .tech-mode [class*="rounded-2xl"],
        .tech-mode [class*="rounded-xl"],
        .tech-mode [class*="rounded-lg"] { border-radius: 5px !important; }
        .tech-mode [class*="rounded-full"] { border-radius: 9999px !important; }

        /* Tabel: padat + garis grid + monospace */
        .tech-mode table { font-family: var(--mono); font-size: 12px; border-collapse: collapse; }
        .tech-mode table th {
            text-transform: uppercase; letter-spacing: .05em; font-size: 10px;
            color: #475569; background: #f1f5f9;
        }
        .tech-mode table th, .tech-mode table td {
            padding-top: .38rem !important; padding-bottom: .38rem !important;
            border: 1px solid #eef2f6;
        }
        .tech-mode table tbody tr:hover { background: #f8fafc; }

        /* Monospace untuk data teknis */
        .tech-mode .font-mono,
        .tech-mode code,
        .tech-mode .text-2xl,
        .tech-mode .text-3xl,
        .tech-mode input,
        .tech-mode select { font-family: var(--mono); }

        /* Heading section: gaya label teknis */
        .tech-mode h1 { font-family: var(--mono); letter-spacing: -.02em; }

        /* Kartu: border lebih tegas, shadow lebih tipis */
        .tech-mode .card,
        .tech-mode [class*="shadow-sm"] { box-shadow: none !important; border-color: #e2e8f0 !important; }

        /* Badge: kotak (bukan pill) untuk kesan teknis */
        .tech-mode .badge,
        .tech-mode [class*="rounded-full"][class*="text-xs"] { border-radius: 4px !important; }
    </style>
    @endif
    @endauth
</head>
<body class="h-full font-sans antialiased text-gray-900 {{ auth()->user()?->chaos_mode ? 'chaos-mode' : '' }} {{ auth()->user()?->hasRole('super_admin') ? 'tech-mode' : '' }}">

<div class="min-h-screen flex" x-data="{ sidebarOpen: false, collapsed: false, helpOpen: false }">

    {{-- ── OVERLAY MOBILE ── --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
         x-cloak></div>

    {{-- ── SIDEBAR ── --}}
    <aside
        :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
            collapsed   ? 'lg:w-16'       : 'lg:w-64',
        ]"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white flex flex-col
               transition-all duration-300 ease-in-out shadow-xl lg:shadow-none">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 shrink-0">
            <img src="{{ asset('images/logo.png') }}"
                 class="logo-icon w-8 h-8 rounded-xl object-cover shrink-0"
                 alt="Logo">
            <div x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 class="ml-3 overflow-hidden">
                <p class="text-gray-900 font-display font-bold text-sm leading-tight truncate">Kurikulum Prodi</p>
                <p class="text-gray-400 text-xs truncate">{{ auth()->user()?->programStudi?->nama ?? '–' }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 py-3 space-y-0.5 overflow-y-auto overflow-x-hidden scrollbar-hide">

            {{-- Dashboard — disembunyikan untuk super admin --}}
            @unless(auth()->user()->hasRole('super_admin'))
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" :collapsed="$collapsed ?? false">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Dashboard</span>
            </x-nav-link>
            @endunless

            {{-- Profil --}}
            <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Profil Saya</span>
            </x-nav-link>

            {{-- ════════════════════════════════════
                 SUPER ADMIN
            ════════════════════════════════════ --}}
            @hasrole('super_admin')
            <p class="nav-section-label" x-show="!collapsed" x-cloak>Super Admin</p>
            <x-nav-link href="{{ route('super-admin.index') }}" :active="request()->routeIs('super-admin.index')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Pusat Kontrol</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.users.index') }}" :active="request()->routeIs('super-admin.users.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Kelola Akun & Role</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.logs.index') }}" :active="request()->routeIs('super-admin.logs.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Log Aktivitas</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.backup.index') }}" :active="request()->routeIs('super-admin.backup.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Backup Database</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.settings.index') }}" :active="request()->routeIs('super-admin.settings.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Pengaturan</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.system.index') }}" :active="request()->routeIs('super-admin.system.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7M3 16.5h.008v.008H3v-.008Zm0-3h.008v.008H3v-.008Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Info Sistem</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.roles.index') }}" :active="request()->routeIs('super-admin.roles.*') || request()->routeIs('super-admin.permissions.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Role & Permission</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.usage-stats.index') }}" :active="request()->routeIs('super-admin.usage-stats.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Statistik Penggunaan</span>
            </x-nav-link>
            <x-nav-link href="{{ route('super-admin.error-logs.index') }}" :active="request()->routeIs('super-admin.error-logs.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Log Error</span>
            </x-nav-link>
            @endhasrole

            {{-- ════════════════════════════════════
                 ADMIN / KAPRODI — disembunyikan untuk super admin
            ════════════════════════════════════ --}}
            @if(auth()->user()->hasAnyRole(['admin','kaprodi']) && !auth()->user()->hasRole('super_admin'))

            <p class="nav-section-label" x-show="!collapsed" x-cloak>Administrasi</p>
            <x-nav-link href="{{ route('kelengkapan') }}" :active="request()->routeIs('kelengkapan')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Status Kelengkapan</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Kelola Akun</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.penugasan.index') }}" :active="request()->routeIs('admin.penugasan.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Penugasan Dosen</span>
            </x-nav-link>
            <x-nav-link href="{{ route('admin.hak-akses') }}" :active="request()->routeIs('admin.hak-akses')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Hak Akses</span>
            </x-nav-link>

            <p class="nav-section-label" x-show="!collapsed" x-cloak>Master Data</p>
            <x-nav-link href="{{ route('master.program-studi.index') }}" :active="request()->routeIs('master.program-studi.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Program Studi</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.profil-lulusan.index') }}" :active="request()->routeIs('master.profil-lulusan.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Profil Lulusan</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.peo.index') }}" :active="request()->routeIs('master.peo.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 3 8l9 5 9-5-9-5Zm-6 8v5c0 2 3 4 6 4s6-2 6-4v-5"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>PEO</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.cpl.index') }}" :active="request()->routeIs('master.cpl.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>CPL</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.mata-kuliah.index') }}" :active="request()->routeIs('master.mata-kuliah.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Mata Kuliah</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.bahan-kajian.index') }}" :active="request()->routeIs('master.bahan-kajian.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Bahan Kajian</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.clo-taxonomi.index') }}" :active="request()->routeIs('master.clo-taxonomi.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>CLO Taxonomy</span>
            </x-nav-link>

            <p class="nav-section-label" x-show="!collapsed" x-cloak>Kurikulum</p>
            <x-nav-link href="{{ route('kurikulum.deskripsi.index') }}" :active="request()->routeIs('kurikulum.deskripsi.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Deskripsi MK</span>
            </x-nav-link>

            <p class="nav-section-label" x-show="!collapsed" x-cloak>Informasi & Laporan</p>
            @php
            $tabelViewActive = request()->routeIs('kurikulum.matriks.tabel*')
                            || request()->routeIs('kurikulum.cpmk.tabel*')
                            || request()->routeIs('kurikulum.sub-cpmk.tabel*');
            @endphp
            <x-nav-link href="{{ route('kurikulum.matriks.tabel1') }}" :active="$tabelViewActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Tabel Kurikulum</span>
            </x-nav-link>
            <x-nav-link href="{{ route('kurikulum.rps.index') }}" :active="request()->routeIs('kurikulum.rps.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>RPS</span>
            </x-nav-link>
            <a href="{{ route('public.home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white text-sm transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span x-show="!collapsed" x-cloak class="flex items-center gap-1">
                    Halaman Publik
                    <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
            </a>

            @endif

            {{-- ════════════════════════════════════
                 DOSEN
            ════════════════════════════════════ --}}
            @hasrole('dosen')

            {{-- SEKSI 1: INPUT --}}
            <p class="nav-section-label" x-show="!collapsed" x-cloak>Input</p>

            {{-- Level CPL Mata Kuliah (Tabel 1, hasil otomatis ke Tabel 2–5) --}}
            <x-nav-link href="{{ route('kurikulum.matriks.input') }}" :active="request()->routeIs('kurikulum.matriks.input')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Level CPL Mata Kuliah</span>
            </x-nav-link>

            @php $cpmkInputActive = request()->routeIs('kurikulum.cpmk.index') || request()->routeIs('kurikulum.cpmk.show'); @endphp
            <x-nav-link href="{{ route('kurikulum.cpmk.index') }}" :active="$cpmkInputActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>CPMK</span>
            </x-nav-link>

            @php $subInputActive = request()->routeIs('kurikulum.sub-cpmk.index') || request()->routeIs('kurikulum.sub-cpmk.show'); @endphp
            <x-nav-link href="{{ route('kurikulum.sub-cpmk.index') }}" :active="$subInputActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Sub CPMK</span>
            </x-nav-link>

            <x-nav-link href="{{ route('kurikulum.asesmen.index') }}" :active="request()->routeIs('kurikulum.asesmen.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Bobot Asesmen</span>
            </x-nav-link>

            <x-nav-link href="{{ route('kurikulum.deskripsi.index') }}" :active="request()->routeIs('kurikulum.deskripsi.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Deskripsi Mata Kuliah</span>
            </x-nav-link>

            {{-- SEKSI 2: REKAP --}}
            @php
            $tabelViewActive = request()->routeIs('kurikulum.matriks.tabel*')
                            || request()->routeIs('kurikulum.cpmk.tabel*')
                            || request()->routeIs('kurikulum.sub-cpmk.tabel*');
            @endphp
            <p class="nav-section-label" x-show="!collapsed" x-cloak>Rekap</p>

            <x-nav-link href="{{ route('kurikulum.matriks.tabel1') }}" :active="$tabelViewActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Tabel Kurikulum</span>
            </x-nav-link>

            <x-nav-link href="{{ route('kurikulum.pengukuran.cpmk') }}" :active="request()->routeIs('kurikulum.pengukuran.cpmk*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Pengukuran</span>
            </x-nav-link>

            <x-nav-link href="{{ route('kurikulum.pengukuran.cpl') }}" :active="request()->routeIs('kurikulum.pengukuran.cpl')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Hasil Pengukuran</span>
            </x-nav-link>

            <p class="nav-section-label !text-red-300" x-show="!collapsed" x-cloak>On Development</p>
            <a href="{{ route('kurikulum.rps.index') }}"
               class="nav-item gap-3 text-red-300 hover:bg-red-50 hover:text-red-400 {{ request()->routeIs('kurikulum.rps.*') ? 'bg-red-50 !text-red-500' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="!collapsed" x-cloak>RPS</span>
            </a>

            @endhasrole

        </nav>

        {{-- Collapse toggle (desktop only) --}}
        <div class="p-3 shrink-0">
            <button @click="collapsed = !collapsed"
                class="hidden lg:flex w-full items-center justify-center gap-2 px-3 py-2 rounded-xl
                       text-gray-400 hover:bg-gray-100 hover:text-gray-600 text-xs transition-all duration-150">
                <svg :class="collapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span x-show="!collapsed" x-cloak class="text-xs">Minimize Menu</span>
            </button>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div :class="collapsed ? 'lg:ml-16' : 'lg:ml-64'"
         class="flex-1 flex flex-col min-h-screen transition-all duration-300">

        {{-- Top header --}}
        <header class="h-14 bg-white flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Tombol Petunjuk + card (hanya muncul jika halaman punya @section('help')) --}}
                @hasSection('navbar_version')
                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-400 text-[11px] font-medium select-none">
                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                    @yield('navbar_version')
                </span>
                @endif

                @hasSection('help')
                <div class="relative hidden sm:block" @click.away="helpOpen = false">
                    <button @click="helpOpen = !helpOpen"
                            :class="helpOpen ? 'bg-blue-50 text-blue-600 ring-1 ring-blue-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @yield('help_title', 'Petunjuk Pengisian')
                    </button>

                    {{-- Popup card --}}
                    <div x-show="helpOpen" x-cloak
                         @keydown.escape.window="helpOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         style="width:440px" class="absolute top-full left-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 flex flex-col overflow-hidden origin-top-left">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 shrink-0 bg-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">?</span>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm leading-tight">@yield('help_title', 'Petunjuk Pengisian')</p>
                                    <p class="text-[10px] text-gray-400 leading-tight truncate max-w-[200px]">@yield('title')</p>
                                </div>
                            </div>
                            <button @click="helpOpen = false"
                                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        {{-- Content --}}
                        <div class="overflow-y-auto max-h-[60vh] px-4 py-4 text-sm text-gray-600">
                            @yield('help')
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="flex items-center gap-2.5">
                {{-- [DEV] Tombol test break reminder — hanya muncul saat APP_DEBUG=true --}}
                @if(config('app.debug'))
                @hasrole('dosen')
                <button onclick="window._testBreakReminder()" title="Test: Break Reminder"
                        class="hidden sm:flex items-center gap-1 px-2 py-1 rounded-lg bg-orange-50 border border-orange-200 text-orange-400 hover:bg-orange-100 text-[10px] font-medium transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Test Break
                </button>
                @endhasrole
                @endif

                {{-- Name + role (desktop) --}}
                <div class="hidden sm:block text-right leading-tight">
                    <p class="text-xs font-medium text-gray-700">{{ auth()->user()?->name }}</p>
                    <p class="text-[10px] text-gray-400 capitalize">{{ auth()->user()?->getRoleNames()->first() }}</p>
                </div>
                {{-- Avatar → link ke profil --}}
                <a href="{{ route('profile.edit') }}"
                   class="user-avatar w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold hover:bg-blue-700 transition-colors shrink-0"
                   title="Profil">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </a>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- Banner pengumuman global --}}
        @php $ann = \App\Models\Setting::all(); @endphp
        @if($ann['announcement_enabled'] === '1' && trim($ann['announcement_text']) !== '')
        <div class="{{ $ann['announcement_type'] === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-sky-50 border-sky-200 text-sky-800' }} border-b px-4 sm:px-6 py-2.5 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46"/>
            </svg>
            <span class="font-medium">{{ $ann['announcement_text'] }}</span>
        </div>
        @endif

        {{-- Banner impersonate --}}
        @if(session('impersonator_id'))
        <div class="bg-amber-500 text-white px-4 sm:px-6 py-2 flex items-center justify-between gap-3 text-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Anda sedang masuk sebagai <strong>{{ auth()->user()->name }}</strong>
            </span>
            <form method="POST" action="{{ route('stop-impersonate') }}" class="shrink-0">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold transition">
                    Kembali ke akun saya
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/>
                    </svg>
                </button>
            </form>
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 px-4 sm:px-6 py-6">
            @if(session('success'))
            <div class="alert-success mb-4">
                <svg class="w-4 h-4 shrink-0 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="alert-error mb-4">
                <svg class="w-4 h-4 shrink-0 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="min-h-[44px] py-2 bg-white flex items-center justify-center px-4 sm:px-6 shrink-0 sticky bottom-0 z-30">
            @php $set = \App\Models\Setting::all(); @endphp
            <p class="text-[13px] text-gray-500 flex items-center gap-2 flex-wrap justify-center">
                <span>
                    {{ $set['footer_text'] }}
                    <a href="{{ $set['footer_credit_url'] ?: '#' }}" target="_blank"
                       class="text-green-600 hover:text-green-700 font-medium transition-colors">{{ $set['footer_credit_name'] }}</a>
                    &nbsp;&copy; {{ $set['footer_year'] }}
                </span>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <a href="https://trakteer.id/ridhoachmad_/tip" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-orange-50 border border-orange-200 text-orange-500 hover:bg-orange-100 hover:text-orange-600 font-medium transition-colors text-xs">
                    ☕ buy me a coffee!
                </a>
            </p>
        </footer>
    </div>

    {{-- ── BREAK REMINDER (dosen only) ─────────────────────────────── --}}
    @hasrole('dosen')
    <div id="break-reminder-modal"
         style="z-index:9999; background-color:rgba(0,0,0,0.75); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px)"
         class="fixed inset-0 hidden items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg text-center p-10 relative">

            {{-- Close button --}}
            <button onclick="dismissBreakReminder()"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Icon --}}
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            {{-- Text --}}
            <h3 id="break-title" class="text-lg font-bold text-gray-800 mb-3"></h3>
            <div id="break-body" class="text-sm leading-relaxed mb-7"></div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button onclick="playKicauMania()"
                        style="background-color:#16a34a"
                        onmouseover="this.style.backgroundColor='#15803d'"
                        onmouseout="this.style.backgroundColor='#16a34a'"
                        class="flex-1 py-3 text-white font-semibold rounded-xl transition-colors text-sm">
                    Kicau Mania
                </button>
                <button onclick="playMBG()"
                        style="background-color:#2563eb"
                        onmouseover="this.style.backgroundColor='#1d4ed8'"
                        onmouseout="this.style.backgroundColor='#2563eb'"
                        class="flex-1 py-3 text-white font-semibold rounded-xl transition-colors text-sm">
                    Mars MBG
                </button>
            </div>

        </div>
    </div>
    @endhasrole

    {{-- ── KICAU MANIA floating player (dosen only) ─────────────────── --}}
    @hasrole('dosen')
    <div id="kicau-player"
         style="position:fixed; inset:0; z-index:10000; display:none; pointer-events:none;
                background-image:url('{{ asset('gif/kicau.gif') }}');
                background-repeat:repeat;
                background-size:auto 33.33vh;">
        {{-- Close button --}}
        <button onclick="closeKicauPlayer()"
                style="position:absolute; top:16px; right:16px;
                       width:36px; height:36px; border-radius:50%;
                       background:rgba(0,0,0,0.5); color:white; border:none;
                       cursor:pointer; font-size:20px; line-height:1;
                       display:flex; align-items:center; justify-content:center;
                       pointer-events:auto;">
            ×
        </button>
        {{-- Audio --}}
        <audio id="kicau-audio" preload="none">
            <source src="{{ asset('sounds/kicaumania.mp3') }}" type="audio/mpeg">
        </audio>
    </div>

    {{-- ── MARS MBG floating player (dosen only) ────────────────────── --}}
    <div id="mbg-player"
         style="position:fixed; inset:0; z-index:10000; display:none; pointer-events:none;
                align-items:center; justify-content:center;">
        <button onclick="closeMBG()"
                style="position:absolute; top:16px; right:16px;
                       width:36px; height:36px; border-radius:50%;
                       background:rgba(0,0,0,0.5); color:white; border:none;
                       cursor:pointer; font-size:20px; line-height:1;
                       display:flex; align-items:center; justify-content:center;
                       pointer-events:auto;">
            ×
        </button>
        <video id="mbg-video"
               src="{{ asset('videos/mbg.mp4') }}"
               preload="none"
               playsinline
               style="max-width:100%; max-height:100%; display:block;">
        </video>
    </div>
    @endhasrole

</div>{{-- /min-h-screen x-data --}}

<script>
(function () {
    @php $brSet = \App\Models\Setting::all(); @endphp
    const BR_ENABLED = {{ $brSet['break_reminder_enabled'] === '1' ? 'true' : 'false' }};
    const BR_MIN = {{ (int) $brSet['break_reminder_min'] }};
    const BR_MAX = {{ max((int) $brSet['break_reminder_min'], (int) $brSet['break_reminder_max']) }};
    const CHECK_MS = 30 * 1000;

    function randomDelay() {
        // Random antara BR_MIN–BR_MAX menit (dalam milidetik)
        return (BR_MIN + Math.random() * Math.max(0, BR_MAX - BR_MIN)) * 60 * 1000;
    }

    // Init session timer — hanya set sekali per sesi browser (reset saat tab ditutup)
    if (!sessionStorage.getItem('dosen_work_start')) {
        sessionStorage.setItem('dosen_work_start', Date.now());
        sessionStorage.setItem('dosen_next_reminder', Date.now() + randomDelay());
    }

    function formatDuration(ms) {
        const h = Math.floor(ms / 3600000);
        const m = Math.floor((ms % 3600000) / 60000);
        if (h > 0) return h + ' jam ' + m + ' menit';
        return m + ' menit';
    }

    function showBreakReminder() {
        const modal = document.getElementById('break-reminder-modal');
        if (!modal) return;
        document.body.appendChild(modal);
        const elapsed = Date.now() - parseInt(sessionStorage.getItem('dosen_work_start') || Date.now());
        const dur     = formatDuration(elapsed);
        // Pilih pesan secara acak
        const msg = breakMessages[Math.floor(Math.random() * breakMessages.length)];
        document.getElementById('break-title').innerHTML = msg.title;
        document.getElementById('break-body').innerHTML  = msg.body(dur);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    window.dismissBreakReminder = function () {
        const modal = document.getElementById('break-reminder-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        sessionStorage.setItem('dosen_next_reminder', Date.now() + randomDelay());
    };

    // Fungsi test — bisa dipanggil dari browser console kapan saja
    window._testBreakReminder = showBreakReminder;

    // Kumpulan pesan istirahat — dipilih acak setiap muncul
    const breakMessages = [
        {
            title: 'Di Mana Kopinya? ☕',
            body: (d) => `<p class="text-gray-500 mb-2">Sudah <strong class="text-red-500">${d}</strong> — dan kami tidak melihat aktivitas ngopi sama sekali.</p><p class="text-gray-400">Tanpa kopi, siapa yang mengisi Bobot Asesmen?<br>Tanpa Bobot Asesmen, bagaimana akreditasi?<br>Jadi sebetulnya, ngopi itu <span class="font-medium text-gray-500">kewajiban akademik</span>.</p>`
        },
        {
            title: 'SURAT EDARAN NO. 001/ISTIRAHAT/2026 📄',
            body: (d) => `<p class="text-gray-500 mb-2">Diberitahukan bahwa Yth. Bapak/Ibu Dosen telah bekerja selama <strong class="text-red-500">${d}</strong> tanpa istirahat.</p><p class="text-gray-400">Sehubungan dengan hal tersebut, kami mengimbau untuk segera meninggalkan meja kerja.<br><br>Atas perhatiannya, kami ucapkan terima kasih.</p>`
        },
        {
            title: 'Peringatan Sistem Terdeteksi 🖥️',
            body: (d) => `<p class="text-gray-500 mb-2"><code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">UPTIME: ${d}</code> &nbsp; <code class="bg-red-50 text-red-500 px-1.5 py-0.5 rounded text-xs">STATUS: Overheating</code></p><p class="text-gray-400">Lanjut kerja tanpa istirahat berisiko <em>typo</em> pada nama Sub-CPMK.<br>Harap lakukan <em>coffee break</em> sebelum data kurikulum ikut error.</p>`
        },
        {
            title: 'Jujur Saja... 😔',
            body: (d) => `<p class="text-gray-500 mb-2">Kami sudah memantau Anda selama <strong class="text-red-500">${d}</strong>. Tidak ada tanda-tanda istirahat. Tidak ada kopi. Tidak ada peregangan.</p><p class="text-gray-400">Kami khawatir. Bukan soal akreditasinya —<br>tapi soal Anda.</p>`
        },
        {
            title: 'Sebuah Pertanyaan Mendalam 🤔',
            body: (d) => `<p class="text-gray-500 mb-2">Jika seorang dosen bekerja <strong class="text-red-500">${d}</strong> nonstop tapi tidak ada yang melihatnya —<br>apakah CPMK-nya tetap selaras?</p><p class="text-gray-400">Istirahat dulu. Jawab pertanyaan ini sambil ngopi.</p>`
        },
        {
            title: 'Sistem Kurikulum 📱 ✓✓',
            body: (d) => `<p class="text-gray-500 mb-2">Halo Pak/Bu, selamat ya sudah kerja <strong class="text-red-500">${d}</strong> 👏</p><p class="text-gray-400">Btw... kapan istirahatnya? 😅<br>Jangan sampai sakit ya, masih banyak kolom yang nunggu 🙏</p>`
        },
    ];

    // Kicau Mania — GIF + Audio
    window.playKicauMania = function () {
        const player = document.getElementById('kicau-player');
        const audio  = document.getElementById('kicau-audio');
        if (!player || !audio) return;
        document.body.appendChild(player);
        player.style.display = 'block';
        audio.load();
        audio.play();
        // Auto-hide saat audio selesai
        audio.onended = function () { closeKicauPlayer(); };
    };

    window.closeKicauPlayer = function () {
        const player = document.getElementById('kicau-player');
        const audio  = document.getElementById('kicau-audio');
        if (!player || !audio) return;
        audio.pause();
        audio.currentTime = 0;
        player.style.display = 'none';
    };

    // Mars MBG — Video
    window.playMBG = function () {
        const player = document.getElementById('mbg-player');
        const video  = document.getElementById('mbg-video');
        if (!player || !video) return;
        document.body.appendChild(player);
        player.style.display = 'flex';
        video.load();
        video.play();
        video.onended = function () { closeMBG(); };
    };

    window.closeMBG = function () {
        const player = document.getElementById('mbg-player');
        const video  = document.getElementById('mbg-video');
        if (!player || !video) return;
        video.pause();
        video.currentTime = 0;
        player.style.display = 'none';
    };

    function checkReminder() {
        const modal = document.getElementById('break-reminder-modal');
        if (!modal || modal.classList.contains('flex')) return; // sudah terbuka
        const next = parseInt(sessionStorage.getItem('dosen_next_reminder') || 0);
        if (Date.now() >= next) {
            showBreakReminder();
        }
    }

    // Mulai check setelah halaman settle, lalu setiap 30 detik — hanya jika diaktifkan
    if (BR_ENABLED) {
        setTimeout(function () {
            checkReminder();
            setInterval(checkReminder, CHECK_MS);
        }, 1500);
    }
})();
</script>

{{-- ── MODE BERANTAKAN (prank visual — fungsi tetap normal) ──────── --}}
@if(auth()->user()?->chaos_mode)
<style>
    @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap');
    .chaos-mode, .chaos-mode * {
        font-family: 'Comic Neue', 'Comic Sans MS', cursive !important;
        letter-spacing: 0.3px;
    }
    /* Warna pelangi berputar di seluruh halaman */
    .chaos-mode { animation: chaosHue 6s linear infinite; }
    @keyframes chaosHue { to { filter: hue-rotate(360deg); } }

    /* Miringkan elemen acak (rotasi diatur via JS pakai --rot) */
    .chaos-mode .card,
    .chaos-mode table,
    .chaos-mode input,
    .chaos-mode select,
    .chaos-mode textarea,
    .chaos-mode button,
    .chaos-mode a.btn-primary,
    .chaos-mode .btn-primary,
    .chaos-mode .btn-secondary,
    .chaos-mode h1, .chaos-mode h2, .chaos-mode h3,
    .chaos-mode [class*="rounded-xl"],
    .chaos-mode [class*="rounded-2xl"] {
        transform: rotate(var(--rot, 0deg)) skew(var(--skw, 0deg)) !important;
        transition: transform .3s ease;
    }
    /* Ikon & gambar bergoyang */
    .chaos-mode svg, .chaos-mode img {
        animation: chaosWobble .5s ease-in-out infinite alternate;
    }
    @keyframes chaosWobble { from { transform: rotate(-8deg) scale(1.05); } to { transform: rotate(8deg) scale(.95); } }

    /* Judul berkedip warna-warni */
    .chaos-mode h1, .chaos-mode h2 {
        animation: chaosRainbow 2s linear infinite, chaosWiggle .4s ease-in-out infinite alternate;
    }
    @keyframes chaosRainbow {
        0% { color:#ef4444 } 25% { color:#22c55e } 50% { color:#3b82f6 } 75% { color:#a855f7 } 100% { color:#ef4444 }
    }
    @keyframes chaosWiggle { from { transform: rotate(-2deg) translateY(-2px); } to { transform: rotate(2deg) translateY(2px); } }
</style>
<script>
    (function () {
        // Beri rotasi & skew acak ke banyak elemen agar tampak berantakan
        function chaosify() {
            const sel = '.card, table, input, select, textarea, button, .btn-primary, .btn-secondary, h1, h2, h3, [class*="rounded-xl"], [class*="rounded-2xl"]';
            document.querySelectorAll(sel).forEach(function (el) {
                const rot = (Math.random() * 10 - 5).toFixed(1);   // -5..5 deg
                const skw = (Math.random() * 6 - 3).toFixed(1);    // -3..3 deg
                el.style.setProperty('--rot', rot + 'deg');
                el.style.setProperty('--skw', skw + 'deg');
            });
        }
        document.addEventListener('DOMContentLoaded', chaosify);
        // Acak ulang tiap 3 detik biar makin "hidup"
        setInterval(chaosify, 3000);
    })();
</script>
@endif

</body>
</html>
