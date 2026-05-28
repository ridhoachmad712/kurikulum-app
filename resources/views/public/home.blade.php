@extends('layouts.public')
@section('title', 'Beranda')

@section('content')

{{-- ─── HERO ──────────────────────────────────────────────────── --}}
<div class="hero-gradient hero-pattern text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        {{-- Badge --}}
        <div class="inline-flex items-center bg-white/10 border border-white/20 rounded-full px-3 py-1 mb-5">
            <p class="text-blue-200 text-[11px] font-semibold uppercase tracking-widest">Sistem Informasi Kurikulum OBE</p>
        </div>

        <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight">
            {{ $prodi->jenjang }} {{ $prodi->nama }}
        </h1>
        <p class="text-blue-200 text-sm mt-2">{{ $prodi->fakultas }} &middot; {{ $prodi->universitas }}</p>

        <p class="text-blue-100/80 text-sm mt-4 max-w-2xl leading-relaxed">
            Aplikasi ini menyajikan dokumentasi kurikulum <span class="text-white font-semibold">{{ $prodi->jenjang }} {{ $prodi->nama }}</span>
            secara transparan dan terstruktur — mulai dari pemetaan CPL, daftar mata kuliah, bahan kajian,
            hingga 12 tabel matriks kurikulum OBE yang dapat diakses oleh seluruh sivitas akademika.
        </p>

        {{-- CTA --}}
        <div class="flex flex-wrap items-center gap-3 mt-6">
            <a href="{{ route('public.kurikulum') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-900 text-sm font-bold rounded-xl shadow hover:bg-blue-50 transition">
                Lihat 12 Tabel Kurikulum
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            <a href="{{ route('public.mata-kuliah') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 border border-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/20 transition">
                Daftar Mata Kuliah
            </a>
        </div>

        {{-- Quick links --}}
        <div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-white/20">
            @foreach([
                ['route' => 'public.profil',      'label' => 'Visi'],
                ['route' => 'public.profil',      'label' => 'Misi'],
                ['route' => 'public.cpl',         'label' => 'CPL'],
                ['route' => 'public.bahan-kajian','label' => 'Bahan Kajian'],
                ['route' => 'public.mata-kuliah', 'label' => 'Mata Kuliah'],
            ] as $link)
            <a href="{{ route($link['route']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 border border-white/20 rounded-full text-xs font-medium text-blue-100 hover:bg-white/20 hover:text-white transition">
                <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
                {{ $link['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ─── KONTEN ─────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── KOLOM KIRI (2/3) ──────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Distribusi per Semester --}}
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-gray-800">Distribusi Mata Kuliah per Semester</h2>
                    <a href="{{ route('public.mata-kuliah') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat semua →</a>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-blue-900 text-white text-xs">
                                <th class="px-5 py-2.5 text-left font-medium">Semester</th>
                                <th class="px-5 py-2.5 text-center font-medium w-20">MK</th>
                                <th class="px-5 py-2.5 text-center font-medium w-20">SKS</th>
                                <th class="px-5 py-2.5 text-left font-medium">Komposisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxSks = $bySemester->max('effective_sks'); @endphp
                            @foreach($bySemester as $smt => $data)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-blue-700 text-white text-[11px] font-bold flex items-center justify-center shrink-0">{{ $smt }}</span>
                                        <span class="text-gray-600 text-xs">Semester {{ $smt }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-2.5 text-center font-semibold text-gray-800 text-xs">{{ $data['effective_count'] }}</td>
                                <td class="px-5 py-2.5 text-center font-semibold text-blue-700 text-xs">{{ $data['effective_sks'] }}</td>
                                <td class="px-5 py-2.5">
                                    @php $pct = $maxSks > 0 ? round($data['effective_sks'] / $maxSks * 100) : 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 min-w-[60px]">
                                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
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
                                <td class="px-5 py-2.5 text-gray-500">Total per jalur</td>
                                <td class="px-5 py-2.5 text-center text-gray-800">{{ $stats['total_mk'] }}</td>
                                <td class="px-5 py-2.5 text-center text-blue-700">{{ $stats['total_sks'] }}</td>
                                <td class="px-5 py-2.5 text-[10px] text-gray-400">W = Wajib · K = Konsentrasi · P = Pilihan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- CPL Ringkas --}}
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-gray-800">Capaian Pembelajaran Lulusan (CPL)</h2>
                    <a href="{{ route('public.cpl') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat detail →</a>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-medium">
                                <th class="px-4 py-2.5 text-left w-20">Kode</th>
                                <th class="px-4 py-2.5 text-left">Deskripsi</th>
                                <th class="px-4 py-2.5 text-center w-20">MK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($cpls as $cpl)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-bold font-mono text-[11px]">{{ $cpl->kode }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-gray-600 leading-relaxed">{{ $cpl->deskripsi }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-[11px] font-medium">{{ $cpl->mk_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        {{-- ── KOLOM KANAN (1/3): Navigasi ──────────────────────── --}}
        <div>

            <h2 class="text-sm font-bold text-gray-800 mb-3">Navigasi</h2>

            {{-- Nav cards — 2 kolom --}}
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['route' => 'public.profil',       'icon' => 'M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z', 'title' => 'Profil Prodi', 'color' => 'bg-blue-50 text-blue-700'],
                    ['route' => 'public.cpl',           'icon' => 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z', 'title' => 'CPL', 'color' => 'bg-emerald-50 text-emerald-600'],
                    ['route' => 'public.mata-kuliah',   'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25', 'title' => 'Mata Kuliah', 'color' => 'bg-amber-50 text-amber-600'],
                    ['route' => 'public.bahan-kajian',  'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z', 'title' => 'Bahan Kajian', 'color' => 'bg-teal-50 text-teal-600'],
                    ['route' => 'public.kurikulum',     'icon' => 'M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75.125a1.125 1.125 0 0 0 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m-18.75-3.75a1.125 1.125 0 0 1 1.125-1.125h1.5a1.125 1.125 0 0 1 1.125 1.125v1.5a1.125 1.125 0 0 1-1.125 1.125m-18 0h.375m-1.5 0a1.125 1.125 0 0 0 1.125 1.125M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V10.5m1.875-6h16.5A1.875 1.875 0 0 1 21.75 6.375V10.5M3.375 4.5h-.375m0 0H1.5M21.75 10.5v8.625A1.875 1.875 0 0 1 19.875 21H4.125A1.875 1.875 0 0 1 2.25 19.125V10.5m19.5 0h-19.5', 'title' => 'Kurikulum', 'color' => 'bg-violet-50 text-violet-600'],
                ] as $nav)
                <a href="{{ route($nav['route']) }}"
                   class="flex flex-col items-start gap-2.5 bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md hover:border-gray-300 transition-all group">
                    <div class="w-9 h-9 rounded-xl {{ $nav['color'] }} flex items-center justify-center shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 group-hover:text-blue-800 transition-colors leading-tight">{{ $nav['title'] }}</p>
                </a>
                @endforeach
            </div>

            {{-- Akses Admin --}}
            <div class="mt-4 bg-gray-50 border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-semibold text-gray-700 mb-1">Panel Admin</p>
                <p class="text-xs text-gray-400 mb-3">Pengelolaan data kurikulum, CPMK, dan asesmen.</p>
                @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 hover:text-blue-900 transition">
                    Buka Dashboard →
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 hover:text-blue-900 transition">
                    Login →
                </a>
                @endauth
            </div>

        </div>
    </div>
</div>

@endsection
