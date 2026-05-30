@extends('layouts.app')
@section('title', $item->exists ? 'Edit Program Studi' : 'Tambah Program Studi')
@section('content')

<div class="max-w-3xl">
    <x-page-header title="{{ $item->exists ? 'Edit Program Studi' : 'Tambah Program Studi' }}"
                   back="{{ route('master.program-studi.index') }}" backLabel="Program Studi"/>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.program-studi.update', $item) : route('master.program-studi.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            {{-- Identitas --}}
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Identitas Program Studi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 @error('kode') border-red-400 @enderror"
                           placeholder="MNJ-S1">
                    @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang <span class="text-red-500">*</span></label>
                    <select name="jenjang" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500">
                        @foreach(['D3','D4','S1','S2','S3'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang', $item->jenjang) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 @error('nama') border-red-400 @enderror"
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
                           class="rounded border-gray-300 text-gray-700 focus:ring-gray-500">
                    <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                </div>
            </div>

            {{-- Visi Misi Tujuan --}}
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mt-7 mb-3">Visi, Misi & Tujuan</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                    <textarea name="visi" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                              placeholder="Visi program studi...">{{ old('visi', $item->visi) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                    <p class="text-xs text-gray-400 mb-1">Tulis setiap poin misi pada baris baru (Enter untuk baris baru).</p>
                    <textarea name="misi" rows="7"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                              placeholder="Misi 1&#10;Misi 2&#10;Misi 3">{{ old('misi', $item->misi) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                    <p class="text-xs text-gray-400 mb-1">Tulis setiap poin tujuan pada baris baru (Enter untuk baris baru).</p>
                    <textarea name="tujuan" rows="7"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                              placeholder="Tujuan 1&#10;Tujuan 2&#10;Tujuan 3">{{ old('tujuan', $item->tujuan) }}</textarea>
                </div>
            </div>

            {{-- Kontak & Lokasi --}}
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mt-7 mb-3">Kontak &amp; Lokasi</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $item->alamat) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                           placeholder="Jl. A. P. Pettarani, Makassar, Sulawesi Selatan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $item->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                           placeholder="manajemen@unm.ac.id">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $item->telepon) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                           placeholder="(0411) 123456">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="text" name="website" value="{{ old('website', $item->website) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500"
                           placeholder="manajemen.feb.unm.ac.id">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn-primary">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.program-studi.index') }}" class="btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
