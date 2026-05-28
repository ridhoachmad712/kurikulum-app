<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} – @yield('title','Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Anti-flash: apply dark class before Alpine loads --}}
    <script>
        (function () {
            var saved   = localStorage.getItem('theme');
            var prefers = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            if ((saved || prefers) === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    {{-- Alpine Stores --}}
    <script>
        document.addEventListener('alpine:init', function () {

            Alpine.store('theme', {
                dark: localStorage.getItem('theme') === 'dark' ||
                      (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggle: function () {
                    this.dark = !this.dark;
                    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', this.dark);
                }
            });

            Alpine.store('sidebar', {
                isExpanded:   window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered:    false,

                toggleExpanded: function () {
                    this.isExpanded   = !this.isExpanded;
                    this.isMobileOpen = false;
                },
                toggleMobileOpen: function () {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen: function (val) {
                    this.isMobileOpen = val;
                },
                setHovered: function (val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>
</head>

<body class="h-full bg-gray-50 dark:bg-gray-950 font-sans antialiased text-gray-900 dark:text-gray-100"
      x-init="
          $store.sidebar.isExpanded = window.innerWidth >= 1280;
          window.addEventListener('resize', function () {
              if (window.innerWidth < 1280) {
                  $store.sidebar.setMobileOpen(false);
                  $store.sidebar.isExpanded = false;
              } else {
                  $store.sidebar.isMobileOpen = false;
                  $store.sidebar.isExpanded   = true;
              }
          });
      ">

    {{-- Mobile sidebar overlay --}}
    <div x-show="$store.sidebar.isMobileOpen"
         @click="$store.sidebar.setMobileOpen(false)"
         class="fixed inset-0 z-[9998] bg-black/50 xl:hidden"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak></div>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main wrapper (shifts right to avoid sidebar) --}}
    <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out"
         :class="{
             'xl:ml-[260px]': $store.sidebar.isExpanded,
             'xl:ml-[72px]':  !$store.sidebar.isExpanded,
         }">

        {{-- Header --}}
        @include('layouts.app-header')

        {{-- Flash messages + page content --}}
        <main class="flex-1 px-4 sm:px-6 py-6">

            @if(session('success'))
            <div class="alert-success mb-5 flex items-start gap-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="alert-error mb-5 flex items-start gap-3">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="h-11 bg-white dark:bg-gray-900
                        border-t border-gray-200 dark:border-gray-800
                        flex items-center justify-center px-4 shrink-0">
            <p class="text-[12px] text-gray-400 dark:text-gray-600">
                Made with love & coffee by
                <a href="https://instagram.com/ridhoachmad_" target="_blank"
                   class="text-gray-600 dark:text-gray-400 font-medium hover:text-brand-600 transition-colors">
                    @ridhoachmad_
                </a>
                &copy; {{ date('Y') }}
            </p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
