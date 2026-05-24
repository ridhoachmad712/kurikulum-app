<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 antialiased">

<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

    {{-- Logo & Title --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-700 rounded-2xl mb-4">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zm5.99 7.176A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Kurikulum Prodi</h1>
        <p class="text-sm text-gray-500 mt-1">S1 Manajemen — FEB UNM</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 w-full max-w-xs p-8">

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
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       placeholder="nama@kampus.ac.id"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                              placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500
                              focus:border-indigo-500 transition @error('email') border-red-400 @enderror">
            </div>

            {{-- Password --}}
            <div class="mb-5" x-data="{ show: false }">
                <div class="flex justify-between mb-1">
                    <label for="password" class="text-sm font-medium text-gray-700">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-gray-700 hover:underline">Lupa kata sandi?</a>
                    @endif
                </div>
                <div class="relative">
                    <input id="password" name="password"
                           :type="show ? 'text' : 'password'"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900
                                  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500
                                  focus:border-indigo-500 transition pr-10 @error('password') border-red-400 @enderror">
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
                       class="w-4 h-4 text-gray-700 border-gray-300 rounded focus:ring-gray-500">
                <label for="remember_me" class="text-sm text-gray-600 cursor-pointer">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-gray-700 hover:bg-gray-700 text-white text-sm font-semibold
                           py-2.5 rounded-lg transition focus:outline-none focus:ring-2
                           focus:ring-gray-500 focus:ring-offset-2">
                Masuk
            </button>
        </form>
    </div>

    <p class="text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} FEB — Universitas Negeri Makassar</p>
</div>

</body>
</html>
