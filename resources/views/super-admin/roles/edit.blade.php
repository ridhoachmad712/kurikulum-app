@extends('layouts.app')
@section('title','Atur Permission: '.$role->name)
@section('content')

<div class="max-w-2xl">
    <x-page-header title="Atur Permission" subtitle="Role: {{ $role->name }}"
                   back="{{ route('super-admin.roles.index') }}" backLabel="Role & Permission"/>

    @if($isCore)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5 text-xs text-amber-700">
        Ini role inti. Akses dasarnya sudah diatur di kode aplikasi; permission di sini bersifat tambahan.
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @if($permissions->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">
            Belum ada permission yang tersedia.
            <a href="{{ route('super-admin.roles.index') }}" class="text-blue-600 hover:underline">Tambahkan permission dulu</a>.
        </p>
        @else
        <form method="POST" action="{{ route('super-admin.roles.update', $role) }}">
            @csrf @method('PATCH')

            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-gray-700">Pilih permission untuk role ini</p>
                <div class="flex gap-2 text-xs">
                    <button type="button" onclick="document.querySelectorAll('.perm-cb').forEach(c=>c.checked=true)" class="text-blue-600 hover:underline">Pilih semua</button>
                    <button type="button" onclick="document.querySelectorAll('.perm-cb').forEach(c=>c.checked=false)" class="text-gray-400 hover:underline">Kosongkan</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($permissions as $perm)
                <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                           {{ in_array($perm->name, $assigned) ? 'checked' : '' }}
                           class="perm-cb w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                    <span class="text-xs font-mono text-gray-700">{{ $perm->name }}</span>
                </label>
                @endforeach
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">Simpan Permission</button>
                <a href="{{ route('super-admin.roles.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
        @endif
    </div>
</div>

@endsection
