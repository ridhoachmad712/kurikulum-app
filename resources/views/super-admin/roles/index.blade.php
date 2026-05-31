@extends('layouts.app')
@section('title','Kelola Role & Permission')
@section('content')

<x-page-header title="Kelola Role & Permission" subtitle="Definisikan role kustom dan atur permission tiap role"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin"/>

<div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 text-xs text-blue-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    <span>Role inti (<strong>super_admin, admin, kaprodi, dosen</strong>) tidak dapat dihapus. Permission yang Anda buat di sini perlu dirujuk di kode aplikasi agar benar-benar membatasi akses.</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Roles --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800">Daftar Role</h2>
            <span class="text-xs text-gray-400">{{ $roles->count() }} role</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">Pengguna</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">Permission</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <span class="font-mono text-xs font-semibold text-gray-800">{{ $role->name }}</span>
                            @if(in_array($role->name, $coreRoles))
                            <span class="ml-1.5 px-1.5 py-0.5 rounded bg-gray-100 text-gray-400 text-[9px] font-semibold uppercase">Inti</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-center text-gray-600 text-xs">{{ $role->users_count }}</td>
                        <td class="px-4 py-2.5 text-center text-gray-600 text-xs">{{ $role->permissions_count }}</td>
                        <td class="px-4 py-2.5">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('super-admin.roles.edit', $role) }}"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Atur permission">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                                @unless(in_array($role->name, $coreRoles))
                                <form method="POST" action="{{ route('super-admin.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        {{-- Tambah role --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs font-semibold text-gray-600 mb-2">Tambah Role Baru</p>
            <form method="POST" action="{{ route('super-admin.roles.store') }}" class="flex items-start gap-2">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="nama_role (huruf kecil & _)"
                           class="form-input py-2 text-sm @error('name') border-red-400 @enderror">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    {{-- Permissions --}}
    <div class="space-y-4">
        <h2 class="text-sm font-bold text-gray-800">Daftar Permission</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            @if($permissions->isEmpty())
            <p class="text-xs text-gray-400 text-center py-4">Belum ada permission. Tambahkan di bawah.</p>
            @else
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($permissions as $perm)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[11px] font-medium">
                    {{ $perm->name }}
                    <form method="POST" action="{{ route('super-admin.permissions.destroy', $perm) }}" onsubmit="return confirm('Hapus permission {{ $perm->name }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600">×</button>
                    </form>
                </span>
                @endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('super-admin.permissions.store') }}" class="flex items-start gap-2 pt-2 border-t border-gray-100">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="mis. kelola-rps"
                           class="form-input py-2 text-sm @error('name') border-red-400 @enderror">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-secondary">+</button>
            </form>
        </div>
    </div>
</div>

@endsection
