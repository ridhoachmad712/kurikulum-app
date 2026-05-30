@extends('layouts.app')
@section('title', 'Deskripsi Mata Kuliah')
@section('content')

<div class="mb-6">
    <h2 class="text-base font-semibold text-gray-800">Deskripsi Mata Kuliah</h2>
    <p class="text-sm text-gray-400 mt-0.5">
        Isi deskripsi singkat tentang isi dan tujuan setiap mata kuliah yang Anda ampu.
    </p>
</div>

<div class="card overflow-hidden">
    <div class="card-header">
        <span class="card-title">Daftar Mata Kuliah</span>
        <span class="text-xs text-gray-400">{{ $mataKuliahs->count() }} mata kuliah</span>
    </div>

    @if($mataKuliahs->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-10 h-10 mx-auto text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-sm font-medium text-gray-400">Belum ada mata kuliah.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="tbl">
                <thead>
                    <tr>
                        <th class="w-8">#</th>
                        <th>Mata Kuliah</th>
                        <th class="w-16 text-center">Smt</th>
                        <th class="w-16 text-center">SKS</th>
                        <th>Deskripsi</th>
                        <th class="w-20 text-center">Status</th>
                        <th class="w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mataKuliahs as $i => $mk)
                    <tr>
                        <td class="text-gray-400">{{ $i + 1 }}</td>
                        <td>
                            <p class="font-mono text-xs text-gray-500 mb-0.5">{{ $mk->kode_rps }}</p>
                            <p class="font-medium text-gray-800 text-sm">{{ $mk->nama }}</p>
                            @if($mk->dosens->isNotEmpty())
                                <p class="text-xs text-gray-400 mt-0.5">{{ $mk->dosens->pluck('name')->join(', ') }}</p>
                            @endif
                        </td>
                        <td class="text-center text-gray-500">{{ $mk->semester ?? '–' }}</td>
                        <td class="text-center text-gray-500">{{ $mk->sks }}</td>
                        <td class="max-w-sm">
                            @if($mk->deskripsi)
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $mk->deskripsi }}</p>
                            @else
                                <span class="text-xs text-gray-300 italic">Belum diisi</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($mk->deskripsi)
                                <span class="badge-green">Terisi</span>
                            @else
                                <span class="badge-gray">Kosong</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('kurikulum.deskripsi.edit', $mk) }}"
                               class="btn-primary btn-sm">
                                {{ $mk->deskripsi ? 'Edit' : 'Isi' }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@section('help')
<div class="space-y-4">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Deskripsi Mata Kuliah</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Deskripsi singkat menjelaskan isi, tujuan, dan ruang lingkup mata kuliah. Informasi ini ditampilkan di halaman publik kurikulum program studi.</p>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Cara Mengisi</h3>
        <ol class="list-decimal list-inside space-y-1 text-xs text-gray-500">
            <li>Klik tombol <strong>Isi</strong> atau <strong>Edit</strong> pada baris mata kuliah.</li>
            <li>Tulis deskripsi singkat (2–4 kalimat) yang menjelaskan MK tersebut.</li>
            <li>Klik <strong>Simpan</strong> untuk menyimpan.</li>
        </ol>
    </div>
    <div class="bg-blue-50 rounded-xl px-4 py-3">
        <p class="text-xs text-blue-700 font-medium">💡 Tips</p>
        <p class="text-xs text-blue-600 mt-1 leading-relaxed">Deskripsi yang baik mencakup: topik utama, metode pembelajaran, dan relevansi MK terhadap profil lulusan.</p>
    </div>
</div>
@endsection
