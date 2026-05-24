@extends('layouts.app')
@section('title','Kelola Akun Pengguna')
@section('content')

<x-page-header title="Kelola Akun Pengguna" subtitle="Manajemen akun admin, kaprodi, dan dosen">
    <x-slot name="actions">
        <x-search-input placeholder="Cari nama atau email..." />
        <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Tambah Akun</a>
    </x-slot>
</x-page-header>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong></p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left font-semibold text-gray-600">Nama</th>
                <th class="px-5 py-3 text-left font-semibold text-gray-600">Username (Email)</th>
                <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Role</th>
                <th class="px-5 py-3 text-center font-semibold text-gray-600 w-28">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $u)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $u->name }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $u->email }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $u->hasRole('kaprodi') ? 'bg-gray-100 text-gray-800' : 'bg-teal-100 text-teal-700' }}">
                        {{ $u->getRoleNames()->first() ?? '–' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center flex justify-center gap-2">
                    <a href="{{ route('admin.users.edit', $u) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Edit</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                          onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-3 py-1 text-xs border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-0">
                <x-empty-state icon="users" title="Belum ada akun pengguna"
                    subtitle="Tambahkan akun admin, kaprodi, atau dosen untuk mulai menggunakan sistem."/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
