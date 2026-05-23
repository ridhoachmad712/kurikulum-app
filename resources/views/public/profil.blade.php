@extends('layouts.public')
@section('title', 'Profil Program Studi')

@section('content')

{{-- ─── HERO ──────────────────────────────────────────────────── --}}
<div class="bg-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="text-indigo-300 text-xs font-semibold uppercase tracking-widest mb-1">Program Studi</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">{{ $prodi->jenjang }} {{ $prodi->nama }}</h1>
        <p class="text-indigo-200 text-sm mt-1">{{ $prodi->fakultas }} &middot; {{ $prodi->universitas }}</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    {{-- ─── VISI ─────────────────────────────────────────────── --}}
    @if($prodi->visi)
    <section>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Visi</h2>
        </div>
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-6 py-5">
            <p class="text-gray-800 leading-relaxed text-base italic">&ldquo;{{ $prodi->visi }}&rdquo;</p>
        </div>
    </section>
    @endif

    {{-- ─── MISI & TUJUAN ────────────────────────────────────── --}}
    @if($prodi->misi || $prodi->tujuan)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Misi --}}
        @if($prodi->misi)
        @php $misiLines = array_values(array_filter(array_map('trim', explode("\n", $prodi->misi)))); @endphp
        <section>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900">Misi</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @foreach($misiLines as $i => $line)
                <div class="flex items-start gap-3 px-5 py-3.5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i+1 }}</span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $line }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Tujuan --}}
        @if($prodi->tujuan)
        @php $tujuanLines = array_values(array_filter(array_map('trim', explode("\n", $prodi->tujuan)))); @endphp
        <section>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900">Tujuan</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @foreach($tujuanLines as $i => $line)
                <div class="flex items-start gap-3 px-5 py-3.5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i+1 }}</span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $line }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>
    @endif

    {{-- ─── PROFIL LULUSAN ───────────────────────────────────── --}}
    @if($profilLulusans->isNotEmpty())
    <section>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-violet-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Profil Lulusan</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($profilLulusans as $pl)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                        <span class="text-violet-700 font-extrabold text-sm">{{ $pl->kode }}</span>
                    </div>
                    <p class="font-semibold text-gray-800 text-sm leading-snug">{{ $pl->nama }}</p>
                </div>
                @if($pl->deskripsi)
                <p class="text-xs text-gray-500 leading-relaxed">{{ $pl->deskripsi }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
