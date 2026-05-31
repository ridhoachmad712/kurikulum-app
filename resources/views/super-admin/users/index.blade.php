@extends('layouts.app')
@section('title','Kelola Akun & Role')
@section('content')

<x-page-header title="Kelola Akun & Role" subtitle="Kelola seluruh akun lintas program studi dan atur rolenya"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin">
    <x-slot name="actions">
        <a href="{{ route('super-admin.users.create') }}" class="btn-primary">+ Tambah Akun</a>
    </x-slot>
</x-page-header>

{{-- Filter role --}}
@php
$roleMeta = [
    'super_admin' => ['Super Admin', 'bg-rose-100 text-rose-700'],
    'admin'       => ['Admin',       'bg-blue-100 text-blue-700'],
    'kaprodi'     => ['Kaprodi',     'bg-violet-100 text-violet-700'],
    'dosen'       => ['Dosen',       'bg-emerald-100 text-emerald-700'],
];
@endphp
<div class="flex items-center gap-2 mb-5 flex-wrap">
    <a href="{{ route('super-admin.users.index') }}"
       class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !$role ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        Semua
    </a>
    @foreach($roleMeta as $key => [$label, $cls])
    <a href="{{ route('super-admin.users.index', ['role' => $key]) }}"
       class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 {{ $role === $key ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        {{ $label }}
        <span class="px-1.5 rounded-full text-[10px] {{ $role === $key ? 'bg-white/20' : 'bg-white' }}">{{ $roleCounts[$key] }}</span>
    </a>
    @endforeach
    <div class="ml-auto"><x-search-input placeholder="Cari nama atau email..." /></div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Program Studi</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-32">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $u)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5">
                    <p class="font-medium text-gray-800">{{ $u->name }}</p>
                    @if($u->id === auth()->id())
                    <span class="text-[10px] text-blue-500 font-medium">— Anda</span>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $u->email }}</td>
                <td class="px-4 py-2.5">
                    <div class="flex flex-wrap gap-1">
                        @forelse($u->roles as $r)
                        @php [$label, $cls] = $roleMeta[$r->name] ?? [$r->name, 'bg-gray-100 text-gray-600']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $cls }}">{{ $label }}</span>
                        @empty
                        <span class="text-xs text-gray-300 italic">tanpa role</span>
                        @endforelse
                    </div>
                </td>
                <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $u->programStudi?->nama ?? '–' }}</td>
                <td class="px-4 py-2.5">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('super-admin.users.edit', $u) }}"
                           class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Edit">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                        </a>
                        @if($u->id !== auth()->id() && !$u->roles->pluck('name')->contains('super_admin'))
                        <form method="POST" action="{{ route('super-admin.users.impersonate', $u) }}"
                              onsubmit="return confirm('Masuk sebagai {{ $u->name }}? Anda akan melihat aplikasi dari sudut pandang akun ini.')">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition" title="Masuk sebagai pengguna ini">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('super-admin.users.reset-password', $u) }}"
                              onsubmit="return confirm('Reset password {{ $u->name }} ke default (password)?')">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition" title="Reset password">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                            </button>
                        </form>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('super-admin.users.destroy', $u) }}"
                              onsubmit="return confirm('Hapus akun {{ $u->name }}? Tindakan ini permanen.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada akun yang cocok.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $users->links() }}</div>
</div>

@endsection
