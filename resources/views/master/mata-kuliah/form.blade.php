@extends('layouts.app')
@section('title', $item->exists ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $item->exists ? route('master.mata-kuliah.update', $item) : route('master.mata-kuliah.store') }}">
            @csrf
            @if($item->exists) @method('PATCH') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                    <select name="program_studi_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        @foreach($programStudis as $ps)
                        <option value="{{ $ps->id }}" {{ old('program_studi_id', $item->program_studi_id) == $ps->id ? 'selected' : '' }}>{{ $ps->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode RPS <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_rps" value="{{ old('kode_rps', $item->kode_rps) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('kode_rps') border-red-400 @enderror"
                               placeholder="RPS001">
                        @error('kode_rps')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" value="{{ old('urutan', $item->urutan ?? 0) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('nama') border-red-400 @enderror">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKS <span class="text-red-500">*</span></label>
                        <input type="number" name="sks" value="{{ old('sks', $item->sks ?? 3) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="1" max="6">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-</option>
                            @for($s=1;$s<=8;$s++)
                            <option value="{{ $s }}" {{ old('semester', $item->semester) == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konsentrasi</label>
                    <input type="text" name="konsentrasi" value="{{ old('konsentrasi', $item->konsentrasi) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="Keuangan / Pemasaran / SDM / dsb">
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="aktif" value="1" id="aktif"
                               {{ old('aktif', $item->aktif ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gray-700">
                        <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_pilihan" value="1" id="is_pilihan"
                               {{ old('is_pilihan', $item->is_pilihan ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-amber-600">
                        <label for="is_pilihan" class="text-sm text-gray-700">MK Pilihan Konsentrasi</label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-5 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                    {{ $item->exists ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('master.mata-kuliah.index') }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
