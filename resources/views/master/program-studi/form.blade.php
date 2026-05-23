@extends('layouts.app')
@section('title', $item->exists ? 'Edit Program Studi' : 'Tambah Program Studi')
@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.program-studi.update', $item) : route('master.program-studi.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('kode') border-red-400 @enderror"
                           placeholder="MNJ-S1">
                    @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang <span class="text-red-500">*</span></label>
                    <select name="jenjang" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['D3','D4','S1','S2','S3'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang', $item->jenjang) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-red-400 @enderror"
                           placeholder="Manajemen">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                    <input type="text" name="fakultas" value="{{ old('fakultas', $item->fakultas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="Fakultas Ekonomi dan Bisnis">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                    <input type="text" name="universitas" value="{{ old('universitas', $item->universitas) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="Universitas Negeri Makassar">
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="aktif" value="1" id="aktif"
                           {{ old('aktif', $item->aktif ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.program-studi.index') }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
