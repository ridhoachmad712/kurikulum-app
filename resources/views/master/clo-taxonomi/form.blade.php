@extends('layouts.app')
@section('title','Edit CLO Taxonomy')
@section('content')
<div class="max-w-lg">
    <x-page-header title="Edit CLO Taxonomy"
                   back="{{ route('master.clo-taxonomi.index') }}" backLabel="CLO Taxonomy"/>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4 grid grid-cols-3 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Kode</p>
                <p class="font-bold text-gray-800 text-lg">{{ $item->kode }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Domain</p>
                <p class="font-semibold text-gray-700">{{ $item->domain }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 text-center">
                <p class="text-xs text-gray-400">Level</p>
                <p class="font-semibold text-gray-700">{{ $item->level }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('master.clo-taxonomi.update', $item) }}">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <input type="text" name="deskripsi" value="{{ old('deskripsi', $item->deskripsi) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn-primary">Simpan</button>
                <a href="{{ route('master.clo-taxonomi.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
