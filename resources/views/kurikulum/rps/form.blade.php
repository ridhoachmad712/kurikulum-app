@extends('layouts.app')
@section('title', isset($rps->id) ? 'Edit RPS' : 'Buat RPS Baru')
@section('content')

<div class="max-w-xl">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kurikulum.rps.index') }}"
           class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-800">
                {{ isset($rps->id) ? 'Edit RPS' : 'Buat RPS Baru' }}
            </h1>
            <p class="text-xs text-gray-400">
                {{ isset($rps->id) ? 'Perbarui informasi RPS (hanya status Draft)' : 'Isi data RPS untuk mata kuliah yang ditugaskan' }}
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST"
              action="{{ isset($rps->id) ? route('kurikulum.rps.update', $rps) : route('kurikulum.rps.store') }}">
            @csrf
            @if(isset($rps->id)) @method('PUT') @endif

            {{-- Mata Kuliah --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                @if(isset($rps->id))
                {{-- Edit mode: non-editable display + hidden --}}
                <div class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                    <span class="font-mono text-gray-700 mr-2">{{ $rps->mataKuliah->kode_rps }}</span>
                    {{ $rps->mataKuliah->nama }}
                </div>
                <input type="hidden" name="mata_kuliah_id" value="{{ $rps->mata_kuliah_id }}">
                @else
                <select name="mata_kuliah_id"
                        class="form-input w-full @error('mata_kuliah_id') border-red-300 @enderror">
                    <option value="">— Pilih Mata Kuliah —</option>
                    @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->id }}"
                            {{ old('mata_kuliah_id', $rps->mata_kuliah_id ?? '') == $mk->id ? 'selected' : '' }}>
                        [{{ $mk->kode_rps }}] {{ $mk->nama }} – Smt {{ $mk->semester }}
                    </option>
                    @endforeach
                </select>
                @endif
                @error('mata_kuliah_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tahun Ajaran --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tahun_ajaran"
                       value="{{ old('tahun_ajaran', $rps->tahun_ajaran ?? '') }}"
                       placeholder="cth. 2024/2025"
                       maxlength="20"
                       class="form-input w-full @error('tahun_ajaran') border-red-300 @enderror">
                @error('tahun_ajaran')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Semester --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Semester <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    @foreach(['Ganjil', 'Genap'] as $sem)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="semester" value="{{ $sem }}"
                               {{ old('semester', $rps->semester ?? '') === $sem ? 'checked' : '' }}
                               class="accent-indigo-600">
                        <span class="text-sm text-gray-700">{{ $sem }}</span>
                    </label>
                    @endforeach
                </div>
                @error('semester')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-gray-700 hover:bg-gray-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    {{ isset($rps->id) ? 'Simpan Perubahan' : 'Buat RPS' }}
                </button>
                <a href="{{ route('kurikulum.rps.index') }}"
                   class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
