@extends('layouts.app')
@section('title','Hak Akses Role')
@section('content')

<div class="mb-6">
    <p class="text-sm text-gray-500">Referensi hak akses setiap role pengguna dalam sistem kurikulum.</p>
</div>

@php
$check  = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100"><svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>';
$cross  = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-50"><svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></span>';
$partial = '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100"><svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/></svg></span>';

$rows = [
    // [label, keterangan, admin, kaprodi, dosen]
    ['section' => 'Umum'],
    ['Dashboard', 'Halaman utama setelah login', $check, $check, $partial],

    ['section' => 'Admin'],
    ['Kelola Akun', 'Buat, ubah, hapus akun dosen & kaprodi', $check, $check, $cross],
    ['Penugasan Dosen', 'Tugaskan dosen ke mata kuliah tertentu', $check, $check, $cross],

    ['section' => 'Master Data'],
    ['Program Studi', 'Kelola data program studi', $check, $check, $cross],
    ['CPL', 'Tambah, ubah, hapus Capaian Pembelajaran Lulusan', $check, $check, $cross],
    ['Mata Kuliah', 'Kelola daftar mata kuliah beserta semester & SKS', $check, $check, $cross],
    ['CLO Taxonomy', 'Edit taksonomi CLO (Kognitif, Afektif, Psikomotorik)', $check, $check, $cross],

    ['section' => 'Kurikulum'],
    ['Matriks MK–CPL (Input Level)', 'Input level keterkaitan setiap MK terhadap CPL', $check, $check, $cross],
    ['Matriks – Tabel 2 s.d. 5', 'Lihat tabel kalkulasi bobot MK–CPL', $check, $check, $cross],
    ['CPMK', 'Isi & ubah Capaian Pembelajaran Mata Kuliah', $check, $check, $partial],
    ['Sub-CPMK & CLO Tertinggi', 'Isi sub-CPMK dan tentukan CLO taksonomi tertinggi', $check, $check, $partial],
    ['Bobot Asesmen', 'Input bobot Tugas, Quis, Kasus/Proyek, UTS, UAS per Sub-CPMK', $check, $check, $partial],
];
@endphp

{{-- Legend --}}
<div class="flex items-center gap-6 mb-5 flex-wrap">
    <div class="flex items-center gap-2 text-sm text-gray-600">
        {!! $check !!} <span>Akses penuh</span>
    </div>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        {!! $partial !!} <span>Akses terbatas</span>
    </div>
    <div class="flex items-center gap-2 text-sm text-gray-600">
        {!! $cross !!} <span>Tidak dapat diakses</span>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-5 py-3 text-left font-semibold text-gray-600 w-56">Fitur / Menu</th>
                <th class="px-5 py-3 text-left font-semibold text-gray-600">Keterangan</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Admin
                    </span>
                </th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span> Kaprodi
                    </span>
                </th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span> Dosen
                    </span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($rows as $row)
                @if(isset($row['section']))
                <tr>
                    <td colspan="5" class="px-5 py-2 bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider border-y border-gray-100">
                        {{ $row['section'] }}
                    </td>
                </tr>
                @else
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $row[0] }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs leading-relaxed">{{ $row[1] }}</td>
                    <td class="px-4 py-3 text-center">{!! $row[2] !!}</td>
                    <td class="px-4 py-3 text-center">{!! $row[3] !!}</td>
                    <td class="px-4 py-3 text-center">{!! $row[4] !!}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

{{-- Catatan akses terbatas --}}
<div class="mt-5 bg-amber-50 border border-amber-100 rounded-xl px-5 py-4">
    <p class="text-xs font-semibold text-amber-700 mb-2">Catatan – Akses Terbatas (Dosen)</p>
    <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside">
        <li><strong>Dashboard</strong>: Dosen hanya melihat ringkasan dan daftar mata kuliah yang ditugaskan kepadanya.</li>
        <li><strong>CPMK, Sub-CPMK, Bobot Asesmen</strong>: Dosen hanya dapat mengedit data untuk mata kuliah yang ditugaskan oleh Kaprodi/Admin melalui menu Penugasan Dosen.</li>
    </ul>
</div>

@endsection
