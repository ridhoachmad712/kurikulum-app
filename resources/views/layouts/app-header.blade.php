<header class="sticky top-0 z-[999] flex h-16 w-full items-center gap-4
              bg-white dark:bg-gray-900
              border-b border-gray-200 dark:border-gray-800
              px-4 sm:px-6 shrink-0">

    {{-- ── Left: toggles + page title ── --}}
    <div class="flex flex-1 items-center gap-3 min-w-0">

        {{-- Desktop sidebar toggle --}}
        <button @click="$store.sidebar.toggleExpanded()"
                class="hidden xl:flex items-center justify-center w-9 h-9 shrink-0 rounded-xl
                       border border-gray-200 dark:border-gray-700
                       text-gray-500 dark:text-gray-400
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       transition-colors duration-150"
                aria-label="Toggle sidebar">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
        </button>

        {{-- Mobile sidebar toggle --}}
        <button @click="$store.sidebar.toggleMobileOpen()"
                class="xl:hidden flex items-center justify-center w-9 h-9 shrink-0 rounded-xl
                       text-gray-500 dark:text-gray-400
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       transition-colors duration-150"
                aria-label="Open menu">
            <svg x-show="!$store.sidebar.isMobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="$store.sidebar.isMobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Page title --}}
        <h1 class="text-sm font-semibold text-gray-700 dark:text-gray-200 font-display truncate">
            @yield('title', 'Dashboard')
        </h1>
    </div>

    {{-- ── Right: actions ── --}}
    <div class="flex items-center gap-2 shrink-0">

        {{-- Dark mode toggle --}}
        <button @click="$store.theme.toggle()"
                class="flex items-center justify-center w-9 h-9 rounded-xl
                       border border-gray-200 dark:border-gray-700
                       text-gray-500 dark:text-gray-400
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       transition-colors duration-150"
                title="Toggle dark mode">
            {{-- Sun (shown in dark mode) --}}
            <svg class="hidden dark:block w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
            </svg>
            {{-- Moon (shown in light mode) --}}
            <svg class="block dark:hidden w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
            </svg>
        </button>

        {{-- User dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-2.5 pl-1 pr-3 py-1.5 rounded-xl
                           hover:bg-gray-100 dark:hover:bg-gray-800
                           transition-colors duration-150">
                {{-- Avatar --}}
                <div class="w-7 h-7 bg-brand-600 rounded-full flex items-center justify-center
                            text-white text-xs font-bold shrink-0">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </div>
                {{-- Name + role (desktop) --}}
                <div class="hidden sm:block text-left leading-tight">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                        {{ auth()->user()?->name }}
                    </p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 capitalize">
                        {{ auth()->user()?->getRoleNames()->first() ?? '' }}
                    </p>
                </div>
                {{-- Chevron --}}
                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 hidden sm:block"
                     :class="open ? 'rotate-180' : ''"
                     style="transition: transform .15s"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown panel --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 top-full mt-2 w-52
                        bg-white dark:bg-gray-800
                        border border-gray-200 dark:border-gray-700
                        rounded-xl shadow-lg py-1.5 z-50"
                 x-cloak>

                {{-- User info header --}}
                <div class="px-4 py-2.5 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">
                        {{ auth()->user()?->name }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">
                        {{ auth()->user()?->email }}
                    </p>
                </div>

                {{-- Links --}}
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300
                          hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    Profil Saya
                </a>

                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400
                                   hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
