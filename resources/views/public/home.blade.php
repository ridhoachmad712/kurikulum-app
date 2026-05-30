@extends('layouts.public')
@section('title', 'Beranda')

@section('content')

{{-- ─── HERO ──────────────────────────────────────────────────── --}}
<div class="hero-gradient text-white relative overflow-hidden">

    {{-- Dekorasi: glow & grid halus --}}
    <div class="pointer-events-none absolute inset-0 hero-pattern opacity-100"></div>
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-blue-400/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-20 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-3.5 py-1.5 mb-6 reveal">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <p class="text-blue-100 text-[11px] font-semibold uppercase tracking-widest">Sistem Informasi Kurikulum OBE</p>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold leading-[1.1] tracking-tight max-w-3xl reveal">
            {{ $prodi->jenjang }} {{ $prodi->nama }}
        </h1>
        <p class="text-blue-200 text-sm sm:text-base mt-3 reveal">{{ $prodi->fakultas }} &middot; {{ $prodi->universitas }}</p>

        <p class="text-blue-100/80 text-sm sm:text-base mt-5 max-w-2xl leading-relaxed reveal">
            Dokumentasi kurikulum <span class="text-white font-semibold">{{ $prodi->jenjang }} {{ $prodi->nama }}</span>
            disajikan secara transparan dan terstruktur — dari pemetaan CPL, daftar mata kuliah, bahan kajian,
            hingga 12 tabel matriks OBE yang dapat diakses seluruh sivitas akademika.
        </p>

        {{-- CTA --}}
        <div class="flex flex-wrap items-center gap-3 mt-8 reveal">
            <a href="{{ route('public.kurikulum') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-white text-blue-900 text-sm font-bold rounded-xl shadow-lg shadow-blue-950/30 hover:bg-blue-50 hover:-translate-y-0.5 transition-all">
                Lihat 12 Tabel Kurikulum
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            <a href="{{ route('public.mata-kuliah') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 border border-white/25 text-white text-sm font-medium rounded-xl hover:bg-white/20 transition">
                Daftar Mata Kuliah
            </a>
        </div>

        {{-- Hero stats — dengan ikon --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-12 reveal">
            @php
            $heroStats = [
                ['val' => $stats['total_mk'],    'label' => 'Mata Kuliah', 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
                ['val' => $stats['total_sks'],   'label' => 'Total SKS',   'icon' => 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3'],
                ['val' => $stats['total_cpl'],   'label' => 'CPL',         'icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'],
                ['val' => $stats['total_dosen'], 'label' => 'Dosen',       'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
            ];
            @endphp
            @foreach($heroStats as $stat)
            <div class="bg-white/[0.07] border border-white/15 rounded-2xl px-5 py-4 backdrop-blur-sm hover:bg-white/[0.12] transition-colors">
                <svg class="w-5 h-5 text-blue-300 mb-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
                <p class="text-3xl font-extrabold text-white leading-none">{{ $stat['val'] }}</p>
                <p class="text-[11px] text-blue-200/80 uppercase tracking-wider mt-2">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Wave separator --}}
    <div class="relative">
        <svg class="w-full h-8 sm:h-12 block" viewBox="0 0 1440 48" preserveAspectRatio="none" fill="none">
            <path d="M0 48h1440V0c-240 32-480 48-720 48S240 32 0 0v48z" fill="#f9fafb"/>
        </svg>
    </div>
</div>

{{-- ─── FITUR APLIKASI ────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-2">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach([
            ['title' => 'Transparansi Kurikulum', 'desc' => 'Seluruh dokumen kurikulum terbuka untuk diakses — profil prodi, CPL, mata kuliah, hingga bahan kajian.', 'icon' => 'M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'tint' => 'blue'],
            ['title' => '12 Tabel Matriks OBE',   'desc' => 'Matriks keterkaitan MK–CPL, CPMK, Sub-CPMK, dan bobot asesmen tersaji lengkap dalam 12 tabel terstruktur.', 'icon' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6', 'tint' => 'emerald'],
            ['title' => 'Pemetaan CPL Terukur',   'desc' => 'Setiap mata kuliah dipetakan ke Capaian Pembelajaran Lulusan secara terukur dan dapat ditelusuri.', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z', 'tint' => 'violet'],
        ] as $f)
        @php
        $tintMap = [
            'blue'    => 'bg-blue-50 text-blue-700 ring-blue-100',
            'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'violet'  => 'bg-violet-50 text-violet-700 ring-violet-100',
        ];
        $tc = $tintMap[$f['tint']];
        @endphp
        <div class="reveal bg-white border border-gray-200/80 rounded-2xl p-6 hover:shadow-lg hover:shadow-gray-200/60 hover:-translate-y-0.5 transition-all">
            <div class="w-12 h-12 rounded-xl ring-4 {{ $tc }} flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-gray-800 mb-1.5">{{ $f['title'] }}</h3>
            <p class="text-xs text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ─── KONTEN UTAMA ──────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── KOLOM KIRI (2/3) ──────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Distribusi per Semester --}}
            <section class="reveal">
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1 h-5 rounded-full bg-blue-700"></span>
                        <h2 class="text-base font-bold text-gray-800">Distribusi Mata Kuliah per Semester</h2>
                    </div>
                    <a href="{{ route('public.mata-kuliah') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition shrink-0">Lihat semua →</a>
                </div>
                {{-- Legenda --}}
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-3 ml-3.5 text-[11px] text-gray-400">
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">W</span> Wajib</span>
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">K</span> Konsentrasi</span>
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">P</span> Pilihan</span>
                    <span class="text-gray-300">·</span>
                    <span>dihitung per 1 mahasiswa per jalur konsentrasi</span>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[480px]">
                        <thead>
                            <tr class="bg-blue-900 text-white text-xs">
                                <th class="px-5 py-3 text-left font-medium">Semester</th>
                                <th class="px-5 py-3 text-center font-medium w-20">MK</th>
                                <th class="px-5 py-3 text-center font-medium w-20">SKS</th>
                                <th class="px-5 py-3 text-left font-medium">Komposisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxSks = $bySemester->max('effective_sks'); @endphp
                            @foreach($bySemester as $smt => $data)
                            <tr class="border-b border-gray-100 hover:bg-blue-50/40 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-7 h-7 rounded-full bg-blue-700 text-white text-[11px] font-bold flex items-center justify-center shrink-0">{{ $smt }}</span>
                                        <span class="text-gray-600 text-xs">Semester {{ $smt }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center font-semibold text-gray-800 text-xs">{{ $data['effective_count'] }}</td>
                                <td class="px-5 py-3 text-center font-semibold text-blue-700 text-xs">{{ $data['effective_sks'] }}</td>
                                <td class="px-5 py-3">
                                    @php $pct = $maxSks > 0 ? round($data['effective_sks'] / $maxSks * 100) : 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-2 min-w-[60px] overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        @if($data['has_kon'])
                                        <span class="text-[10px] text-gray-400 whitespace-nowrap">
                                            @if($data['wajib_count'] > 0)
                                                {{ $data['wajib_count'] }}W
                                                @if($data['kon_wajib'] > 0) + {{ $data['kon_wajib'] }}K @endif
                                                @if($data['kon_pilihan'] > 0) + {{ $data['kon_pilihan'] }}P @endif
                                            @else
                                                {{ $data['kon_wajib'] }}W
                                                @if($data['kon_pilihan'] > 0) + {{ $data['kon_pilihan'] }} dari {{ $data['kon_pilihan'] * 2 }}P @endif
                                            @endif
                                        </span>
                                        @else
                                        <span class="text-[11px] text-gray-400 w-8 text-right">{{ $pct }}%</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50 text-xs font-semibold border-t border-gray-200">
                                <td class="px-5 py-3 text-gray-500">Total per jalur</td>
                                <td class="px-5 py-3 text-center text-gray-800">{{ $stats['total_mk'] }}</td>
                                <td class="px-5 py-3 text-center text-blue-700">{{ $stats['total_sks'] }}</td>
                                <td class="px-5 py-3 text-[10px] text-gray-400">W = Wajib · K = Konsentrasi · P = Pilihan</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>{{-- /overflow-x-auto --}}
                </div>
            </section>

            {{-- CPL — kartu --}}
            <section class="reveal">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-1 h-5 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base font-bold text-gray-800">Capaian Pembelajaran Lulusan</h2>
                    </div>
                    <a href="{{ route('public.cpl') }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition shrink-0">Lihat detail →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($cpls as $cpl)
                    <div class="group bg-white border border-gray-200 rounded-2xl p-4 hover:border-blue-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-block px-2.5 py-1 bg-blue-600 text-white rounded-lg font-bold font-mono text-[11px]">{{ $cpl->kode }}</span>
                            <span class="inline-flex items-center gap-1 text-[11px] text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                                </svg>
                                {{ $cpl->mk_count }} MK
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed line-clamp-3">{{ $cpl->deskripsi }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

        </div>

        {{-- ── KOLOM KANAN (1/3) ─────────────────────────────────── --}}
        <div class="reveal">

            <div class="flex items-center gap-2.5 mb-4">
                <span class="w-1 h-5 rounded-full bg-violet-600"></span>
                <h2 class="text-base font-bold text-gray-800">Jelajahi</h2>
            </div>

            {{-- Nav cards --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    ['route' => 'public.profil',       'icon' => 'M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z', 'title' => 'Profil Prodi', 'color' => 'bg-blue-50 text-blue-700 group-hover:bg-blue-600 group-hover:text-white'],
                    ['route' => 'public.cpl',           'icon' => 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z', 'title' => 'CPL', 'color' => 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white'],
                    ['route' => 'public.mata-kuliah',   'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25', 'title' => 'Mata Kuliah', 'color' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white'],
                    ['route' => 'public.bahan-kajian',  'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z', 'title' => 'Bahan Kajian', 'color' => 'bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white'],
                    ['route' => 'public.kurikulum',     'icon' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6', 'title' => 'Kurikulum', 'color' => 'bg-violet-50 text-violet-600 group-hover:bg-violet-600 group-hover:text-white'],
                    ['route' => 'public.profil',        'icon' => 'M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z', 'title' => 'Visi & Misi', 'color' => 'bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white'],
                ] as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="group flex flex-col items-start gap-3 bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-lg hover:shadow-gray-200/60 hover:border-gray-300 hover:-translate-y-0.5 transition-all">
                    <div class="w-10 h-10 rounded-xl {{ $nav['color'] }} flex items-center justify-center shrink-0 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 group-hover:text-gray-900 transition-colors leading-tight">{{ $nav['title'] }}</p>
                </a>
                @endforeach
            </div>

            {{-- Info Kontak --}}
            @if($prodi->alamat || $prodi->email || $prodi->telepon || $prodi->website)
            <div class="mt-5 bg-gradient-to-br from-blue-900 to-blue-800 text-white rounded-2xl p-5">
                <p class="text-xs font-bold mb-3.5 text-blue-100">Kontak &amp; Lokasi</p>
                <ul class="space-y-3 text-xs text-blue-100/90">
                    @if($prodi->alamat)
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-blue-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <span class="leading-relaxed">{{ $prodi->alamat }}</span>
                    </li>
                    @endif
                    @if($prodi->email)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <a href="mailto:{{ $prodi->email }}" class="hover:text-white transition break-all">{{ $prodi->email }}</a>
                    </li>
                    @endif
                    @if($prodi->telepon)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <a href="tel:{{ $prodi->telepon }}" class="hover:text-white transition">{{ $prodi->telepon }}</a>
                    </li>
                    @endif
                    @if($prodi->website)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                        <a href="{{ \Illuminate\Support\Str::startsWith($prodi->website, ['http://','https://']) ? $prodi->website : 'https://'.$prodi->website }}"
                           target="_blank" rel="noopener" class="hover:text-white transition break-all">{{ $prodi->website }}</a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif

            {{-- Akses dosen/admin --}}
            <p class="mt-5 text-center text-[11px] text-gray-400">
                @auth
                Dosen &amp; pengelola: <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium transition">buka Dashboard →</a>
                @else
                Dosen &amp; pengelola: <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition">login di sini →</a>
                @endauth
            </p>

        </div>
    </div>
</div>

@endsection
