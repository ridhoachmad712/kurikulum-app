@extends('layouts.app')
@section('title', $item->exists ? 'Edit Profil Lulusan' : 'Tambah Profil Lulusan')
@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.profil-lulusan.update', $item) : route('master.profil-lulusan.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 @error('kode') border-red-400 @enderror"
                           placeholder="PL1">
                    @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan <span class="text-red-500">*</span></label>
                    <input type="number" name="urutan" value="{{ old('urutan', $item->urutan ?: '') }}"
                           min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 @error('urutan') border-red-400 @enderror"
                           placeholder="1">
                    @error('urutan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama / Jabatan Profil <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 @error('nama') border-red-400 @enderror"
                           placeholder="Wirausahawan Pemula">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="5"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                              placeholder="Deskripsi lulusan...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.profil-lulusan.index') }}" class="btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
