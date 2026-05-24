<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} – @yield('title','Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900">

<div class="min-h-screen flex" x-data="{ sidebarOpen: false, collapsed: false }">

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
        class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-900 flex flex-col
               transition-all duration-300 ease-in-out shadow-xl lg:shadow-none">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 border-b border-indigo-800/60 shrink-0">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zm5.99 7.176A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
            <div x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 class="ml-3 overflow-hidden">
                <p class="text-white font-display font-bold text-sm leading-tight truncate">Kurikulum Prodi</p>
                <p class="text-indigo-300 text-xs truncate">{{ auth()->user()?->programStudi?->nama ?? '–' }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 py-3 space-y-0.5 overflow-y-auto overflow-x-hidden">

            {{-- Dashboard --}}
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" :collapsed="$collapsed ?? false">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Dashboard</span>
            </x-nav-link>

            @hasanyrole('admin|kaprodi')
            <p class="nav-section-label" x-show="!collapsed" x-cloak>Admin</p>
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
            @endhasanyrole

            <p class="nav-section-label" x-show="!collapsed" x-cloak>Master Data</p>
            @hasanyrole('admin|kaprodi')
            <x-nav-link href="{{ route('master.program-studi.index') }}" :active="request()->routeIs('master.program-studi.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Program Studi</span>
            </x-nav-link>
            @endhasanyrole
            @hasanyrole('admin|kaprodi')
            <x-nav-link href="{{ route('master.profil-lulusan.index') }}" :active="request()->routeIs('master.profil-lulusan.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Profil Lulusan</span>
            </x-nav-link>
            @endhasanyrole
            <x-nav-link href="{{ route('master.cpl.index') }}" :active="request()->routeIs('master.cpl.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>CPL</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.bahan-kajian.index') }}" :active="request()->routeIs('master.bahan-kajian.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Bahan Kajian</span>
            </x-nav-link>
            <x-nav-link href="{{ route('master.mata-kuliah.index') }}" :active="request()->routeIs('master.mata-kuliah.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Mata Kuliah</span>
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

            {{-- Tabel Kurikulum (1–12) --}}
            @php
            $tabelActive = request()->routeIs('kurikulum.matriks.*')
                        || request()->routeIs('kurikulum.cpmk.tabel*')
                        || request()->routeIs('kurikulum.sub-cpmk.tabel*');
            @endphp
            <x-nav-link href="{{ route('kurikulum.matriks.input') }}" :active="$tabelActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Tabel Kurikulum</span>
            </x-nav-link>

            {{-- Input CPMK --}}
            @php $cpmkInputActive = request()->routeIs('kurikulum.cpmk.index') || request()->routeIs('kurikulum.cpmk.show'); @endphp
            <x-nav-link href="{{ route('kurikulum.cpmk.index') }}" :active="$cpmkInputActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Input CPMK</span>
            </x-nav-link>

            {{-- Input Sub-CPMK --}}
            @php $subInputActive = request()->routeIs('kurikulum.sub-cpmk.index') || request()->routeIs('kurikulum.sub-cpmk.show'); @endphp
            <x-nav-link href="{{ route('kurikulum.sub-cpmk.index') }}" :active="$subInputActive">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Input Sub-CPMK</span>
            </x-nav-link>

            {{-- Bobot Asesmen --}}
            <x-nav-link href="{{ route('kurikulum.asesmen.index') }}" :active="request()->routeIs('kurikulum.asesmen.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>Bobot Asesmen</span>
            </x-nav-link>

            {{-- RPS --}}
            <x-nav-link href="{{ route('kurikulum.rps.index') }}" :active="request()->routeIs('kurikulum.rps.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </x-slot>
                <span x-show="!collapsed" x-cloak>RPS</span>
            </x-nav-link>
        </nav>

        {{-- User info + collapse button --}}
        <div class="border-t border-indigo-800/60 p-3 shrink-0">
            <div x-show="!collapsed" x-cloak class="flex items-center gap-2.5 px-2 py-1.5 mb-2">
                <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-white text-xs font-medium truncate">{{ auth()->user()?->name }}</p>
                    <p class="text-indigo-400 text-xs truncate">{{ auth()->user()?->getRoleNames()->first() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar"
                        class="text-indigo-400 hover:text-white transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Collapse toggle (desktop only) --}}
            <button @click="collapsed = !collapsed"
                class="hidden lg:flex w-full items-center justify-center gap-2 px-3 py-2 rounded-xl
                       text-indigo-400 hover:bg-white/10 hover:text-white text-xs transition-all duration-150">
                <svg :class="collapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span x-show="!collapsed" x-cloak class="text-xs">Ciutkan</span>
            </button>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div :class="collapsed ? 'lg:ml-16' : 'lg:ml-64'"
         class="flex-1 flex flex-col min-h-screen transition-all duration-300">

        {{-- Top header --}}
        <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-sm font-semibold text-gray-700 font-display">@yield('title','Dashboard')</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-block text-xs text-gray-400">{{ auth()->user()?->name }}</span>
                <a href="{{ route('profile.edit') }}"
                   class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold hover:bg-indigo-700 transition-colors">
                    {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                </a>
            </div>
        </header>

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
        <footer class="h-10 bg-white border-t border-gray-200 flex items-center justify-center px-4 sm:px-6 shrink-0 sticky bottom-0 z-30">
            <p class="text-xs text-gray-400">
                &copy; 2026 &nbsp;|&nbsp; This App made with love and coffee by
                <a href="https://instagram.com/ridhoachmad_" target="_blank"
                   class="text-indigo-400 hover:text-indigo-600 font-medium transition-colors">@ridhoachmad_</a>
            </p>
        </footer>
    </div>

</div>
</body>
</html>
