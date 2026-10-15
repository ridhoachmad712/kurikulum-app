@extends('layouts.app')
@section('title', 'Program Learning Outcomes')
@section('content')

<x-page-header title="Program Learning Outcomes (PLO)" subtitle="{{ $prodi->nama }} — kelola capaian lulusan program studi">
    <x-slot name="actions">
        <a href="{{ route('master.plo.create') }}" class="btn-primary">+ Tambah PLO</a>
    </x-slot>
</x-page-header>

@if($items->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-500 text-sm">
    Belum ada PLO. <a href="{{ route('master.plo.create') }}" class="text-blue-700 font-semibold hover:underline">Tambahkan PLO pertama</a>.
</div>
@else
<div class="space-y-4">
    @foreach($items as $plo)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <span class="inline-flex w-fit px-3 py-2 rounded-xl bg-blue-50 text-blue-800 font-bold text-sm">{{ $plo->kode }}</span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">{{ $plo->nama }}</p>
                <p class="text-gray-600 text-sm mt-1 leading-relaxed whitespace-pre-line">{{ $plo->deskripsi }}</p>
                <p class="text-gray-400 text-xs mt-2">Urutan {{ $plo->urutan }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('master.plo.edit', $plo) }}" class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100">Edit</a>
                <form method="POST" action="{{ route('master.plo.destroy', $plo) }}" onsubmit="return confirm('Hapus {{ $plo->kode }}?')">
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
