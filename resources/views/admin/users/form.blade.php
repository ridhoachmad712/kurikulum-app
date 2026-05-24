@extends('layouts.app')
@section('title', $user ? 'Edit Akun: '.$user->name : 'Tambah Akun Baru')
@section('content')

<div class="max-w-lg">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-700 hover:underline mb-5 inline-block">&larr; Kembali</a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST"
              action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if($user) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name"
                           value="{{ old('name', $user?->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 @error('name') border-red-400 @enderror"
                           placeholder="Nama lengkap dosen" required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username (Email)</label>
                    <input type="email" name="email"
                           value="{{ old('email', $user?->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 @error('email') border-red-400 @enderror"
                           placeholder="email@domain.com" required>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                        @if($user)<span class="text-gray-400 font-normal">(kosongkan jika tidak diganti)</span>@endif
                    </label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 @error('password') border-red-400 @enderror"
                           placeholder="{{ $user ? '••••••' : 'Minimal 6 karakter' }}"
                           {{ $user ? '' : 'required' }}>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">
                        <option value="dosen"   {{ old('role', $user?->getRoleNames()->first()) === 'dosen'   ? 'selected' : '' }}>Dosen</option>
                        <option value="kaprodi" {{ old('role', $user?->getRoleNames()->first()) === 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                    {{ $user ? 'Simpan Perubahan' : 'Buat Akun' }}
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
