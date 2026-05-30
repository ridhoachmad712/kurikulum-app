@extends('layouts.public')
@section('title', 'Profil Program Studi')

@section('content')

{{-- ─── PAGE HEADER ────────────────────────────────────────────── --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center gap-1 text-xs text-gray-400 mb-2">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700 transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-600 font-medium">Profil Prodi</span>
        </nav>
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-1">Program Studi</p>
        <h1 class="text-xl font-bold text-gray-900">{{ $prodi->jenjang }} {{ $prodi->nama }}</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $prodi->fakultas }} &middot; {{ $prodi->universitas }}</p>
    </div>
</div>

{{-- ─── KONTEN ─────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    {{-- ─── VISI ─────────────────────────────────────────────── --}}
    @if($prodi->visi)
    <section id="visi" class="scroll-mt-24">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-6 rounded-full bg-blue-700 inline-block"></span>
            <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Visi</h2>
        </div>
        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-r-2xl px-7 py-5">
            <p class="text-gray-800 leading-relaxed text-base sm:text-lg italic font-medium">
                &ldquo;{{ $prodi->visi }}&rdquo;
            </p>
        </div>
    </section>
    @endif

    {{-- ─── MISI & TUJUAN ────────────────────────────────────── --}}
    @if($prodi->misi || $prodi->tujuan)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Misi --}}
        @if($prodi->misi)
        @php $misiLines = array_values(array_filter(array_map('trim', explode("\n", $prodi->misi)))); @endphp
        <section id="misi" class="scroll-mt-24">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-6 rounded-full bg-emerald-500 inline-block"></span>
                <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Misi</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @foreach($misiLines as $i => $line)
                <div class="flex items-start gap-4 px-5 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-emerald-50/40 transition-colors">
                    <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-extrabold flex items-center justify-center shrink-0 mt-0.5">
                        {{ $i + 1 }}
                    </span>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $line }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Tujuan --}}
        @if($prodi->tujuan)
        @php $tujuanLines = array_values(array_filter(array_map('trim', explode("\n", $prodi->tujuan)))); @endphp
        <section id="tujuan" class="scroll-mt-24">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-6 rounded-full bg-amber-500 inline-block"></span>
                <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Tujuan</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @foreach($tujuanLines as $i => $line)
                <div class="flex items-start gap-4 px-5 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-amber-50/40 transition-colors">
                    <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 text-xs font-extrabold flex items-center justify-center shrink-0 mt-0.5">
                        {{ $i + 1 }}
                    </span>
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
        <div class="flex items-center gap-2 mb-6">
            <span class="w-1 h-6 rounded-full bg-violet-600 inline-block"></span>
            <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Profil Lulusan</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($profilLulusans as $pl)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 flex flex-col gap-3 hover:border-violet-300 hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-violet-600 flex items-center justify-center shrink-0">
                    <span class="text-white font-extrabold text-sm">{{ $pl->kode }}</span>
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm leading-snug mb-1.5">{{ $pl->nama }}</p>
                    @if($pl->deskripsi)
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $pl->deskripsi }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
