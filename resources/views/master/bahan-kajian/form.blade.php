@extends('layouts.app')
@section('title', $item->exists ? 'Edit Bahan Kajian' : 'Tambah Bahan Kajian')
@section('content')

<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('master.bahan-kajian.index') }}" class="text-sm text-gray-700 hover:underline">← Kembali ke daftar</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.bahan-kajian.update', $item) : route('master.bahan-kajian.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('kode') border-red-400 @enderror"
                               placeholder="BK1">
                        @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" value="{{ old('urutan', $item->urutan ?? 0) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Kajian <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('nama') border-red-400 @enderror"
                           placeholder="Manajemen Strategik">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                              placeholder="Deskripsi singkat bahan kajian...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-5 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.bahan-kajian.index') }}"
                   class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
