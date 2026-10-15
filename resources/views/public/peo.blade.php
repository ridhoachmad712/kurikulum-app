@extends('layouts.public')
@section('title', 'Program Educational Objectives (PEO)')
@section('content')

<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-600 font-medium">PEO</span>
        </nav>
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-1">Tujuan Pendidikan Program Studi</p>
        <h1 class="text-xl font-bold text-gray-900">Program Educational Objectives (PEO)</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $prodi->nama }} &middot; {{ $peos->count() }} PEO</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($peos->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-sm text-gray-500">PEO belum tersedia.</div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            @foreach($peos as $peo)
                <article class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 h-full">
                    <span class="inline-flex rounded-lg bg-blue-50 text-blue-700 px-3 py-1 text-xs font-bold">{{ $peo->kode }}</span>
                    <h2 class="text-lg font-bold text-gray-900 mt-4">{{ $peo->nama }}</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mt-3 whitespace-pre-line">{{ $peo->deskripsi }}</p>
                </article>
            @endforeach
        </div>
    @endif
</div>

@endsection
