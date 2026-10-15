@extends('layouts.app')
@section('title', 'Unggah RPS')
@section('content')

<x-page-header title="Unggah RPS" subtitle="Unggah PDF Rencana Pembelajaran Semester untuk mata kuliah yang Anda ampu." />

<div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 mb-5 text-sm text-blue-800">
    Pilih berkas PDF maksimal 10 MB. Satu mata kuliah memiliki satu RPS aktif; unggahan baru akan menggantikan berkas sebelumnya dan langsung tersedia di halaman publik.
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4 mb-5 text-sm text-red-700">
    {{ $errors->first() }}
</div>
@endif

@if($mataKuliahs->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 p-10 text-center text-sm text-gray-500">
    Belum ada mata kuliah aktif yang ditugaskan kepada Anda.
</div>
@else
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3 text-left">Mata Kuliah</th>
                    <th class="px-4 py-3 text-left">RPS Aktif</th>
                    <th class="px-4 py-3 text-left">Unggah PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($mataKuliahs as $mk)
                <tr>
                    <td class="px-5 py-4 align-top">
                        <p class="font-semibold text-gray-900">{{ $mk->nama }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $mk->kode_rps }} &middot; Semester {{ $mk->semester }}</p>
                    </td>
                    <td class="px-4 py-4 align-top">
                        @if($mk->rpsDocument)
                            <a href="{{ route('public.mata-kuliah.rps', $mk) }}" target="_blank" rel="noopener"
                               class="font-semibold text-blue-700 hover:underline">Lihat RPS</a>
                            <p class="text-xs text-gray-500 mt-1 break-all">{{ $mk->rpsDocument->original_name }}</p>
                            <p class="text-xs text-gray-400 mt-1">Diperbarui {{ $mk->rpsDocument->updated_at->format('d M Y H:i') }}
                                @if($mk->rpsDocument->uploader) oleh {{ $mk->rpsDocument->uploader->name }} @endif
                            </p>
                        @else
                            <span class="text-xs text-gray-400">Belum diunggah</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 align-top">
                        <form method="POST" action="{{ route('kurikulum.rps-documents.store', $mk) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <input type="file" name="file" accept=".pdf,application/pdf" required
                                   aria-label="Berkas RPS untuk {{ $mk->nama }}"
                                   class="block max-w-56 text-xs text-gray-600 file:mr-2 file:rounded-lg file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-xs file:font-medium file:text-gray-700 hover:file:bg-gray-200">
                            <button type="submit" class="btn-primary whitespace-nowrap">{{ $mk->rpsDocument ? 'Ganti RPS' : 'Unggah' }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
