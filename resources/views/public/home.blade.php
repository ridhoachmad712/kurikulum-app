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

        {{-- Hero stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-7">
            @foreach([
                ['val' => $stats['total_mk'],    'label' => 'Mata Kuliah'],
                ['val' => $stats['total_sks'],   'label' => 'Total SKS'],
                ['val' => $stats['total_cpl'],   'label' => 'CPL'],
                ['val' => $stats['total_dosen'], 'label' => 'Dosen'],
            ] as $stat)
            <div class="bg-white/10 border border-white/15 rounded-2xl px-4 py-3">
                <p class="text-2xl sm:text-3xl font-extrabold text-white leading-none">{{ $stat['val'] }}</p>
                <p class="text-[11px] text-blue-200/80 uppercase tracking-wider mt-1.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

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

        {{-- Quick links — loncat ke bagian Profil Prodi --}}
        <div class="flex flex-wrap items-center gap-2 mt-8 pt-6 border-t border-white/20">
            <span class="text-[11px] text-blue-200/70 uppercase tracking-wider mr-1">Profil Prodi:</span>
            @foreach([
                ['url' => route('public.profil').'#visi',   'label' => 'Visi'],
                ['url' => route('public.profil').'#misi',   'label' => 'Misi'],
                ['url' => route('public.profil').'#tujuan', 'label' => 'Tujuan'],
            ] as $link)
            <a href="{{ $link['url'] }}"
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
                <div class="flex items-center justify-between mb-1.5">
                    <h2 class="text-sm font-bold text-gray-800">Distribusi Mata Kuliah per Semester</h2>
                    <a href="{{ route('public.mata-kuliah') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat semua →</a>
                </div>
                {{-- Legenda komposisi --}}
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-3 text-[11px] text-gray-400">
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">W</span> Wajib</span>
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">K</span> Konsentrasi</span>
                    <span class="inline-flex items-center gap-1"><span class="font-bold text-gray-600">P</span> Pilihan</span>
                    <span class="text-gray-300">·</span>
                    <span>angka pada kolom Komposisi dihitung per 1 mahasiswa per jalur konsentrasi</span>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[480px]">
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
                    </div>{{-- /overflow-x-auto --}}
                </div>
            </section>

            {{-- CPL Ringkas --}}
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-gray-800">Capaian Pembelajaran Lulusan (CPL)</h2>
                    <a href="{{ route('public.cpl') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat detail →</a>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                    <table class="w-full text-xs min-w-[420px]">
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
                    </div>{{-- /overflow-x-auto --}}
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
                    ['route' => 'public.peo',          'icon' => 'M12 3 3 8l9 5 9-5-9-5Zm-6 8v5c0 2 3 4 6 4s6-2 6-4v-5', 'title' => 'PEO', 'color' => 'bg-indigo-50 text-indigo-700'],
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

            {{-- Info Kontak --}}
            @if($prodi->alamat || $prodi->email || $prodi->telepon || $prodi->website)
            <div class="mt-4 bg-white border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-bold text-gray-700 mb-3">Kontak &amp; Lokasi</p>
                <ul class="space-y-2.5 text-xs text-gray-600">
                    @if($prodi->alamat)
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <span class="leading-relaxed">{{ $prodi->alamat }}</span>
                    </li>
                    @endif
                    @if($prodi->email)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <a href="mailto:{{ $prodi->email }}" class="hover:text-blue-700 transition break-all">{{ $prodi->email }}</a>
                    </li>
                    @endif
                    @if($prodi->telepon)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <a href="tel:{{ $prodi->telepon }}" class="hover:text-blue-700 transition">{{ $prodi->telepon }}</a>
                    </li>
                    @endif
                    @if($prodi->website)
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                        <a href="{{ \Illuminate\Support\Str::startsWith($prodi->website, ['http://','https://']) ? $prodi->website : 'https://'.$prodi->website }}"
                           target="_blank" rel="noopener" class="hover:text-blue-700 transition break-all">{{ $prodi->website }}</a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif

            {{-- Akses dosen/admin — tautan halus, tombol utama ada di navbar --}}
            <p class="mt-4 text-center text-[11px] text-gray-400">
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
