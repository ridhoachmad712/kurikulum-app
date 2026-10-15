@extends('layouts.app')
@section('title', 'Program Educational Objectives')
@section('content')

<x-page-header title="Program Educational Objectives (PEO)" subtitle="{{ $prodi->nama }} — kelola tujuan pendidikan program studi">
    <x-slot name="actions">
        <a href="{{ route('master.peo.create') }}" class="btn-primary">+ Tambah PEO</a>
    </x-slot>
</x-page-header>

@if($items->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-500 text-sm">
    Belum ada PEO. <a href="{{ route('master.peo.create') }}" class="text-blue-700 font-semibold hover:underline">Tambahkan PEO pertama</a>.
</div>
@else
<div class="space-y-4">
    @foreach($items as $peo)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <span class="inline-flex w-fit px-3 py-2 rounded-xl bg-blue-50 text-blue-800 font-bold text-sm">{{ $peo->kode }}</span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">{{ $peo->nama }}</p>
                <p class="text-gray-600 text-sm mt-1 leading-relaxed whitespace-pre-line">{{ $peo->deskripsi }}</p>
                <p class="text-gray-400 text-xs mt-2">Urutan {{ $peo->urutan }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('master.peo.edit', $peo) }}" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100">Edit</a>
                <form method="POST" action="{{ route('master.peo.destroy', $peo) }}" onsubmit="return confirm('Hapus {{ $peo->kode }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
