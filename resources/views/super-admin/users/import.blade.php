@extends('layouts.app')
@section('title','Import Akun Massal')
@section('content')

<div class="max-w-2xl">
    <x-page-header title="Import Akun Massal" subtitle="Buat atau perbarui banyak akun sekaligus dari file Excel/CSV"
                   back="{{ route('super-admin.users.index') }}" backLabel="Kelola Akun"/>

    {{-- Panduan --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 mb-5 text-sm">
        <p class="font-semibold text-blue-800 mb-2">Cara Pakai</p>
        <ol class="list-decimal list-inside space-y-1 text-xs text-blue-700">
            <li>Unduh template, isi data sesuai kolom, simpan sebagai <strong>.xlsx</strong> atau <strong>.csv</strong>.</li>
            <li>Unggah file di bawah, lalu klik <strong>Proses Import</strong>.</li>
            <li>Akun dicocokkan berdasarkan <strong>email</strong> — yang sudah ada akan diperbarui, yang baru dibuat.</li>
        </ol>
        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px] text-blue-600">
            <span><strong>nama</strong> — wajib</span>
            <span><strong>email</strong> — wajib, unik</span>
            <span><strong>role</strong> — dosen/kaprodi/admin/super_admin</span>
            <span><strong>program_studi</strong> — kode/nama (opsional)</span>
            <span><strong>password</strong> — opsional (default: password)</span>
            <span><strong>nip</strong> — opsional</span>
        </div>
        <a href="{{ route('super-admin.users.import.template') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-semibold text-blue-700 hover:text-blue-900">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Unduh Template
        </a>
    </div>

    @if(session('import_skipped') && count(session('import_skipped')))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-5">
        <p class="text-sm font-semibold text-amber-800 mb-1.5">{{ count(session('import_skipped')) }} baris dilewati:</p>
        <ul class="list-disc list-inside text-xs text-amber-700 space-y-0.5 max-h-40 overflow-y-auto">
            @foreach(session('import_skipped') as $s)<li>{{ $s }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('super-admin.users.import') }}" enctype="multipart/form-data">
            @csrf
            <label class="form-label">File Excel / CSV <span class="text-red-500">*</span></label>
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                   class="w-full text-sm text-gray-600 border border-gray-300 rounded-xl px-3 py-2.5
                          file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-gray-700 file:text-white file:text-xs file:font-medium file:cursor-pointer hover:file:bg-gray-800
                          @error('file') border-red-400 @enderror">
            @error('file')<p class="form-error">{{ $message }}</p>@enderror

            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn-primary">Proses Import</button>
                <a href="{{ route('super-admin.users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
