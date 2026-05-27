<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kurikulum') — S1 Manajemen FEB UNM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important}
        .hero-gradient { background: linear-gradient(135deg, #0f2557 0%, #1e3a8a 50%, #1e40af 100%); }
        .hero-pattern  { background-image: radial-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(135deg, #0f2557 0%, #1e3a8a 50%, #1e40af 100%); background-size: 20px 20px, auto; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-800">

{{-- ─── HEADER ─────────────────────────────────────────── --}}
<header class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-200"
        x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('public.home') }}" class="flex items-center gap-3 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-blue-700 flex items-center justify-center shadow-sm">
                    <span class="text-white font-extrabold text-sm">M</span>
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-bold text-gray-900">S1 Manajemen</p>
                    <p class="text-[11px] text-gray-400 hidden sm:block">FEB · Universitas Negeri Makassar</p>
                </div>
            </a>

            {{-- Desktop Nav --}}
            @php
            $navLinks = [
                ['route' => 'public.home',         'pattern' => 'public.home',          'label' => 'Beranda'],
                ['route' => 'public.profil',       'pattern' => 'public.profil',        'label' => 'Profil Prodi'],
                ['route' => 'public.cpl',          'pattern' => 'public.cpl',           'label' => 'CPL'],
                ['route' => 'public.bahan-kajian', 'pattern' => 'public.bahan-kajian',  'label' => 'Bahan Kajian'],
                ['route' => 'public.mata-kuliah',  'pattern' => 'public.mata-kuliah*',  'label' => 'Mata Kuliah'],
                ['route' => 'public.kurikulum',    'pattern' => 'public.kurikulum',     'label' => 'Kurikulum'],
            ];
            @endphp
            <nav class="hidden md:flex items-center gap-1">
                @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                          {{ request()->routeIs($link['pattern'])
                             ? 'bg-blue-50 text-blue-800 font-semibold'
                             : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                @auth
                <a href="{{ route('dashboard') }}"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3"/>
                    </svg>
                    Panel Admin
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Masuk Admin
                </a>
                @endauth

                {{-- Mobile hamburger --}}
                <button @click="open = !open"
                        class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
                    <svg x-show="!open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak
         class="md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1 shadow-lg">
        @foreach($navLinks as $link)
        <a href="{{ route($link['route']) }}"
           class="block px-4 py-2.5 text-sm font-medium rounded-xl
                  {{ request()->routeIs($link['pattern'])
                     ? 'bg-blue-50 text-blue-800 font-semibold'
                     : 'text-gray-600 hover:bg-gray-50' }}">
            {{ $link['label'] }}
        </a>
        @endforeach
        <div class="pt-2 mt-2 border-t border-gray-100">
            @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-semibold text-blue-700">Panel Admin →</a>
            @else
            <a href="{{ route('login') }}"     class="block px-4 py-2.5 text-sm font-semibold text-blue-700">Masuk Admin →</a>
            @endauth
        </div>
    </div>
</header>

{{-- ─── CONTENT ────────────────────────────────────────── --}}
<main>@yield('content')</main>

{{-- ─── FOOTER ─────────────────────────────────────────── --}}
<footer class="bg-blue-950 text-blue-200 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-700 flex items-center justify-center">
                        <span class="text-white font-bold text-xs">M</span>
                    </div>
                    <span class="text-white font-bold text-sm">S1 Manajemen</span>
                </div>
                <p class="text-xs leading-relaxed text-blue-300">
                    Fakultas Ekonomi dan Bisnis<br>
                    Universitas Negeri Makassar<br>
                    Kurikulum Berbasis OBE – KKNI
                </p>
            </div>
            <div>
                <p class="text-white text-xs font-semibold uppercase tracking-wider mb-3">Navigasi</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.home') }}"       class="text-blue-300 hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('public.profil') }}"     class="text-blue-300 hover:text-white transition">Profil Prodi</a></li>
                    <li><a href="{{ route('public.cpl') }}"          class="text-blue-300 hover:text-white transition">CPL Program Studi</a></li>
                    <li><a href="{{ route('public.bahan-kajian') }}" class="text-blue-300 hover:text-white transition">Bahan Kajian</a></li>
                    <li><a href="{{ route('public.mata-kuliah') }}"  class="text-blue-300 hover:text-white transition">Mata Kuliah</a></li>
                    <li><a href="{{ route('public.kurikulum') }}"  class="text-blue-300 hover:text-white transition">Kurikulum</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white text-xs font-semibold uppercase tracking-wider mb-3">Admin</p>
                <ul class="space-y-2 text-sm">
                    @auth
                    <li><a href="{{ route('dashboard') }}" class="text-blue-300 hover:text-white transition">Panel Admin</a></li>
                    @else
                    <li><a href="{{ route('login') }}"     class="text-blue-300 hover:text-white transition">Masuk Admin</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-blue-900 text-xs text-center text-blue-700">
            © {{ date('Y') }} Program Studi S1 Manajemen · FEB UNM · Sistem Informasi Kurikulum
        </div>
    </div>
</footer>


</body>
</html>
