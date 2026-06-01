<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ \App\Models\Setting::get('app_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/3logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased">

<div class="min-h-screen flex">

    {{-- ── KIRI: Gambar (sembunyi di mobile) — 3/4 lebar ── --}}
    <div class="hidden lg:block lg:w-3/4 relative">
        <img src="{{ asset('images/bg.jpeg') }}" alt="Kampus"
             class="absolute inset-0 w-full h-full object-cover">
        {{-- Overlay gradien + branding --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-950/85 via-blue-900/55 to-blue-800/25"></div>
        <div class="relative h-full flex flex-col justify-between p-12 text-white">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/2logo.png') }}" alt="Logo" class="w-11 h-11 object-contain drop-shadow">
                <span class="font-semibold tracking-wide text-sm">{{ \App\Models\Setting::get('institution_name') }}</span>
            </div>
            <div>
                <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight max-w-md drop-shadow">
                    {{ \App\Models\Setting::get('app_name') }}
                </h2>
                <p class="text-blue-100/90 text-sm mt-3 max-w-md leading-relaxed">
                    Dokumentasi kurikulum berbasis OBE — pemetaan CPL, mata kuliah, dan matriks capaian pembelajaran dalam satu sistem.
                </p>
            </div>
            <p class="text-blue-200/70 text-xs">© {{ date('Y') }} {{ \App\Models\Setting::get('institution_name') }}</p>
        </div>
    </div>

    {{-- ── KANAN: Form login — 1/4 lebar ── --}}
    <div class="w-full lg:w-1/4 flex items-center justify-center px-6 py-12 bg-slate-50">
        <div class="w-full max-w-sm">

            {{-- Logo & judul (untuk mobile / konsistensi) --}}
            <div class="text-center mb-8">
                <img src="{{ asset('images/2logo.png') }}" alt="Logo" class="w-16 h-16 object-contain mx-auto mb-3">
                <h1 class="text-xl font-bold text-gray-900">{{ \App\Models\Setting::get('app_name') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ \App\Models\Setting::get('institution_name') }}</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="nama@kampus.ac.id"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                                  focus:border-blue-500 transition @error('email') border-red-400 @enderror">
                </div>

                {{-- Password --}}
                <div class="mb-5" x-data="{ show: false }">
                    <div class="flex justify-between mb-1">
                        <label for="password" class="text-sm font-medium text-gray-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" name="password"
                               :type="show ? 'text' : 'password'"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                      placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40
                                      focus:border-blue-500 transition pr-10 @error('password') border-red-400 @enderror">
                        <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2 mb-5">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember_me" class="text-sm text-gray-600 cursor-pointer">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold
                               py-2.5 rounded-lg transition focus:outline-none focus:ring-2
                               focus:ring-blue-500 focus:ring-offset-2">
                    Masuk
                </button>
            </form>

            <div class="text-center mt-8">
                <p class="text-xs text-gray-400">Sistem Informasi Kurikulum</p>
                <p class="text-xs text-gray-400 mt-0.5">Developed by
                    <a href="https://instagram.com/ridhoachmad_" target="_blank" class="hover:text-gray-600 transition-colors">@ridhoachmad_</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
