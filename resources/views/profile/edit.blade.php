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
