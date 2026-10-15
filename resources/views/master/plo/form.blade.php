@extends('layouts.app')
@section('title', $item->exists ? 'Edit PLO' : 'Tambah PLO')
@section('content')

<div class="max-w-2xl">
    <x-page-header title="{{ $item->exists ? 'Edit PLO' : 'Tambah PLO' }}" back="{{ route('master.plo.index') }}" backLabel="Program Learning Outcomes"/>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.plo.update', $item) : route('master.plo.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input id="kode" name="kode" type="text" maxlength="20" required value="{{ old('kode', $item->kode) }}" placeholder="PLO1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('kode') border-red-400 @enderror">
                    @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="urutan" class="block text-sm font-medium text-gray-700 mb-1">Urutan <span class="text-red-500">*</span></label>
                    <input id="urutan" name="urutan" type="number" min="1" max="65535" required value="{{ old('urutan', $item->urutan ?: '') }}" placeholder="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('urutan') border-red-400 @enderror">
                    @error('urutan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama PLO <span class="text-red-500">*</span></label>
                    <input id="nama" name="nama" type="text" maxlength="255" required value="{{ old('nama', $item->nama) }}" placeholder="Professional accomplishment" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('nama') border-red-400 @enderror">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('deskripsi') border-red-400 @enderror" placeholder="Rumusan capaian lulusan...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">{{ $item->exists ? 'Perbarui' : 'Simpan' }}</button>
                <a href="{{ route('master.plo.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
