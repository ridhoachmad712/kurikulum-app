@extends('layouts.app')
@section('title', $user ? 'Edit Akun: '.$user->name : 'Tambah Akun')
@section('content')

<div class="max-w-2xl">
    <x-page-header title="{{ $user ? 'Edit Akun' : 'Tambah Akun Baru' }}"
                   subtitle="{{ $user ? $user->email : 'Buat akun baru dan tetapkan role serta program studinya' }}"
                   back="{{ route('super-admin.users.index') }}" backLabel="Kelola Akun"/>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST"
              action="{{ $user ? route('super-admin.users.update', $user) : route('super-admin.users.store') }}">
            @csrf
            @if($user) @method('PATCH') @endif

            @php $currentRole = $user ? ($user->roles->pluck('name')->contains('super_admin') ? 'super_admin' : $user->roles->pluck('name')->first()) : old('role'); @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user?->name) }}"
                           class="form-input @error('name') border-red-400 @enderror" placeholder="Nama lengkap">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                           class="form-input @error('email') border-red-400 @enderror" placeholder="nama@unm.ac.id">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">NIP <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="nip" value="{{ old('nip', $user?->nip) }}"
                           class="form-input" placeholder="NIP">
                </div>

                <div>
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="form-input @error('role') border-red-400 @enderror">
                        <option value="">— Pilih role —</option>
                        @foreach(['super_admin' => 'Super Admin', 'admin' => 'Admin', 'kaprodi' => 'Kaprodi', 'dosen' => 'Dosen'] as $val => $label)
                        <option value="{{ $val }}" {{ $currentRole === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                    <p class="form-hint">Super Admin otomatis juga memiliki akses Admin.</p>
                </div>

                <div>
                    <label class="form-label">Program Studi <span class="text-red-500">*</span></label>
                    <select name="program_studi_id" class="form-input @error('program_studi_id') border-red-400 @enderror">
                        @foreach($programStudis as $ps)
                        <option value="{{ $ps->id }}" {{ old('program_studi_id', $user?->program_studi_id) == $ps->id ? 'selected' : '' }}>{{ $ps->nama }}</option>
                        @endforeach
                    </select>
                    @error('program_studi_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">
                        Password
                        @if($user)<span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                        @else<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="password" name="password"
                           class="form-input @error('password') border-red-400 @enderror"
                           placeholder="{{ $user ? '••••••••' : 'Minimal 6 karakter' }}">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">{{ $user ? 'Simpan Perubahan' : 'Buat Akun' }}</button>
                <a href="{{ route('super-admin.users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
