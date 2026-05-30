@extends('layouts.app')
@section('title', 'Edit Deskripsi – ' . $mataKuliah->kode_rps)
@section('content')

<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-5">
        <a href="{{ route('kurikulum.deskripsi.index') }}" class="hover:text-gray-600 transition-colors">Deskripsi MK</a>
        <span>›</span>
        <span class="text-gray-600 font-medium">{{ $mataKuliah->kode_rps }}</span>
    </div>

    <div class="card p-6">

        {{-- Info MK --}}
        <div class="mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="font-mono text-xs text-gray-500">{{ $mataKuliah->kode_rps }}</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $mataKuliah->nama }}</p>
                    <div class="flex items-center gap-3 mt-1">
                        @if($mataKuliah->semester)
                            <span class="text-xs text-gray-400">Semester {{ $mataKuliah->semester }}</span>
                        @endif
                        @if($mataKuliah->sks)
                            <span class="text-xs text-gray-400">{{ $mataKuliah->sks }} SKS</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('kurikulum.deskripsi.update', $mataKuliah) }}" method="POST"
              x-data="{ chars: {{ strlen($mataKuliah->deskripsi ?? '') }}, max: 3000 }">
            @csrf
            @method('PATCH')

            <div class="mb-5">
                <label for="deskripsi" class="form-label">
                    Deskripsi Mata Kuliah
                </label>
                <p class="text-xs text-gray-400 mb-2">
                    Jelaskan secara singkat isi, ruang lingkup, dan tujuan pembelajaran mata kuliah ini.
                </p>
                <textarea id="deskripsi" name="deskripsi" rows="8"
                    class="form-input resize-none @error('deskripsi') border-red-400 @enderror"
                    placeholder="Contoh: Mata kuliah ini membahas konsep-konsep dasar manajemen sumber daya manusia, meliputi rekrutmen, seleksi, pelatihan, dan pengembangan karyawan…"
                    maxlength="3000"
                    x-on:input="chars = $event.target.value.length"
                    >{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>

                <div class="flex justify-between mt-1">
                    @error('deskripsi')
                        <p class="form-error">{{ $message }}</p>
                    @else
                        <p class="form-hint">Isi dengan kalimat yang jelas dan informatif.</p>
                    @enderror
                    <p class="text-xs text-gray-400 ml-auto" :class="chars > max * 0.9 ? 'text-amber-500' : ''">
                        <span x-text="chars"></span>/{{ number_format(3000) }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Deskripsi
                </button>
                <a href="{{ route('kurikulum.deskripsi.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

@endsection

@section('help')
<div class="space-y-4">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Menulis Deskripsi yang Baik</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Deskripsi mata kuliah sebaiknya mencakup 3 hal utama:</p>
        <ul class="list-disc list-inside space-y-1 text-xs text-gray-500 mt-2">
            <li><strong>Topik</strong> — apa yang dipelajari dalam MK ini</li>
            <li><strong>Metode</strong> — bagaimana MK ini diajarkan</li>
            <li><strong>Relevansi</strong> — kaitannya dengan profil lulusan</li>
        </ul>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Contoh</h3>
        <p class="text-xs text-gray-400 italic leading-relaxed">"Mata kuliah ini membahas konsep dasar manajemen keuangan perusahaan, meliputi analisis laporan keuangan, penganggaran modal, dan manajemen risiko. Menggunakan pendekatan studi kasus nyata untuk mempersiapkan mahasiswa dalam pengambilan keputusan keuangan di dunia kerja."</p>
    </div>
    <div class="bg-blue-50 rounded-xl px-4 py-3">
        <p class="text-xs text-blue-700 font-medium">💡 Tips</p>
        <p class="text-xs text-blue-600 mt-1 leading-relaxed">Panjang ideal 2–4 kalimat. Hindari terlalu teknis — deskripsi ini juga dibaca oleh calon mahasiswa di halaman publik.</p>
    </div>
</div>
@endsection
