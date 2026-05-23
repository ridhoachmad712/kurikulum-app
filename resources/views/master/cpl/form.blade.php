@extends('layouts.app')
@section('title', $item->exists ? 'Edit CPL' : 'Tambah CPL')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.cpl.update', $item) : route('master.cpl.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                    <select name="program_studi_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        @foreach($programStudis as $ps)
                        <option value="{{ $ps->id }}" {{ old('program_studi_id', $item->program_studi_id) == $ps->id ? 'selected' : '' }}>
                            {{ $ps->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="CPL1">
                        @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" value="{{ old('urutan', $item->urutan ?? 0) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi CPL <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('deskripsi') border-red-400 @enderror"
                              placeholder="Lulusan mampu...">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan', $item->keterangan) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="CPL UNM / CPL APSMBI">
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.cpl.index') }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
