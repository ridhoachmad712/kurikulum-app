@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ── PAGE HEADER ── --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-sm text-gray-400 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- ── UPDATE NAMA & EMAIL ── --}}
    <div class="card p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Informasi Akun</h2>

        @if(session('status') === 'profile-updated')
        <div class="alert-success mb-4">
            <svg class="w-4 h-4 shrink-0 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Profil berhasil diperbarui.</span>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                {{-- Nama --}}
                <div>
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text"
                           class="form-input @error('name') border-red-400 @enderror"
                           value="{{ old('name', $user->name) }}"
                           required autocomplete="name">
                    @error('name')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email"
                           class="form-input @error('email') border-red-400 @enderror"
                           value="{{ old('email', $user->email) }}"
                           required autocomplete="username">
                    @error('email')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role (read-only) --}}
                <div>
                    <label class="form-label">Role</label>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @foreach($user->getRoleNames() as $role)
                        <span class="badge-indigo capitalize">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ── TEMA WARNA ── --}}
    @php
    $themes = [
        'blue'    => ['label' => 'Biru',      'color' => '#2563EB', 'hover' => '#1D4ED8'],
        'indigo'  => ['label' => 'Indigo',    'color' => '#4F46E5', 'hover' => '#4338CA'],
        'violet'  => ['label' => 'Violet',    'color' => '#7C3AED', 'hover' => '#6D28D9'],
        'emerald' => ['label' => 'Hijau',     'color' => '#059669', 'hover' => '#047857'],
        'teal'    => ['label' => 'Teal',      'color' => '#0D9488', 'hover' => '#0F766E'],
        'rose'    => ['label' => 'Merah',     'color' => '#E11D48', 'hover' => '#BE123C'],
        'orange'  => ['label' => 'Oranye',    'color' => '#EA580C', 'hover' => '#C2410C'],
        'pink'    => ['label' => 'Pink',      'color' => '#DB2777', 'hover' => '#BE185D'],
        'yellow'  => ['label' => 'Kuning',    'color' => '#CA8A04', 'hover' => '#A16207'],
        'slate'   => ['label' => 'Abu-abu',   'color' => '#475569', 'hover' => '#334155'],
    ];
    $currentTheme = $user->theme_color ?? 'blue';
    @endphp
    <div class="card p-6"
         x-data="{
            current: '{{ $currentTheme }}',
            saving: false,
            themes: {
                blue:    { color: '#2563EB', hover: '#1D4ED8' },
                indigo:  { color: '#4F46E5', hover: '#4338CA' },
                violet:  { color: '#7C3AED', hover: '#6D28D9' },
                emerald: { color: '#059669', hover: '#047857' },
                teal:    { color: '#0D9488', hover: '#0F766E' },
                rose:    { color: '#E11D48', hover: '#BE123C' },
                orange:  { color: '#EA580C', hover: '#C2410C' },
                pink:    { color: '#DB2777', hover: '#BE185D' },
                yellow:  { color: '#CA8A04', hover: '#A16207' },
                slate:   { color: '#475569', hover: '#334155' },
            },
            applyTheme(name) {
                const t = this.themes[name];
                const css = `.nav-item-active,.logo-icon,.user-avatar{background-color:${t.color}!important}.btn-primary{background-color:${t.color}!important}.btn-primary:hover{background-color:${t.hover}!important}.user-avatar:hover{background-color:${t.hover}!important}`;
                document.getElementById('theme-style').textContent = css;
            },
            async select(name) {
                this.current = name;
                this.applyTheme(name);
                this.saving = true;
                await fetch('{{ route('profile.theme') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ color: name }),
                });
                this.saving = false;
            }
         }">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-700">Tema Warna</h2>
            <span x-show="saving" x-cloak class="text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Menyimpan...
            </span>
            <span x-show="!saving" x-cloak class="text-xs text-gray-400">Klik untuk mengubah</span>
        </div>

        <div class="flex flex-wrap gap-3">
            @foreach($themes as $name => $t)
            <button type="button" @click="select('{{ $name }}')"
                    title="{{ $t['label'] }}"
                    :style="{ backgroundColor: '{{ $t['color'] }}', outline: current === '{{ $name }}' ? '3px solid {{ $t['color'] }}' : 'none', outlineOffset: '3px' }"
                    :class="current === '{{ $name }}' ? 'scale-110 shadow-md' : 'hover:scale-105 hover:shadow'"
                    class="relative w-9 h-9 rounded-full transition-all duration-150 focus:outline-none">
                <span x-show="current === '{{ $name }}'" class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 mt-3">Perubahan langsung diterapkan ke seluruh tampilan aplikasi.</p>
    </div>

    {{-- ── GANTI PASSWORD ── --}}
    <div class="card p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Ganti Password</h2>

        @if(session('status') === 'password-updated')
        <div class="alert-success mb-4">
            <svg class="w-4 h-4 shrink-0 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Password berhasil diperbarui.</span>
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Password lama --}}
                <div>
                    <label class="form-label" for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password"
                           class="form-input @error('current_password', 'updatePassword') border-red-400 @enderror"
                           autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password baru --}}
                <div>
                    <label class="form-label" for="password">Password Baru</label>
                    <input id="password" name="password" type="password"
                           class="form-input @error('password', 'updatePassword') border-red-400 @enderror"
                           autocomplete="new-password">
                    @error('password', 'updatePassword')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi password baru --}}
                <div>
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           class="form-input"
                           autocomplete="new-password">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
