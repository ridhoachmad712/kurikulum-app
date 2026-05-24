@extends('layouts.public')
@section('title', 'Beranda')

@section('content')

{{-- ─── HERO ──────────────────────────────────────────────────── --}}
<div class="bg-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-2">Sistem Informasi Kurikulum</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">{{ $prodi->jenjang }} {{ $prodi->nama }}</h1>
        <p class="text-blue-100 text-sm mt-1.5">{{ $prodi->fakultas }} &middot; {{ $prodi->universitas }}</p>

        {{-- Stat badges --}}
        <div class="flex flex-wrap gap-6 mt-6 pt-6 border-t border-blue-600/50">
            @foreach([
                ['val' => $stats['total_mk'],   'label' => 'Mata Kuliah'],
                ['val' => $stats['total_cpl'],  'label' => 'CPL'],
                ['val' => $stats['total_sks'],  'label' => 'Total SKS'],
                ['val' => $stats['total_cpmk'], 'label' => 'CPMK'],
            ] as $s)
            <div>
                <p class="text-3xl font-extrabold">{{ $s['val'] }}</p>
                <p class="text-blue-200 text-xs mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ─── KONTEN ─────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── KOLOM KIRI (2/3): Distribusi Semester + CPL Overview ─ --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Distribusi per Semester --}}
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1 h-6 rounded-full bg-blue-700 inline-block"></span>
                    <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Distribusi Mata Kuliah per Semester</h2>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-blue-900 text-white text-xs">
                                <th class="px-5 py-3 text-left font-semibold">Semester</th>
                                <th class="px-5 py-3 text-center font-semibold">Jumlah MK</th>
                                <th class="px-5 py-3 text-center font-semibold">Total SKS</th>
                                <th class="px-5 py-3 text-left font-semibold w-1/2">Proporsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxSks = $bySemester->max('sks'); @endphp
                            @foreach($bySemester as $smt => $data)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-extrabold flex items-center justify-center">{{ $smt }}</span>
                                        <span class="text-gray-700 font-medium text-xs">Semester {{ $smt }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center font-bold text-gray-800">{{ $data['count'] }}</td>
                                <td class="px-5 py-3 text-center font-bold text-blue-800">{{ $data['sks'] }}</td>
                                <td class="px-5 py-3">
                                    @php $pct = $maxSks > 0 ? round($data['sks'] / $maxSks * 100) : 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-400 w-8 text-right">{{ $data['sks'] }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold text-sm">
                                <td class="px-5 py-3 text-gray-600">Total</td>
                                <td class="px-5 py-3 text-center text-gray-800">{{ $stats['total_mk'] }}</td>
                                <td class="px-5 py-3 text-center text-blue-800">{{ $stats['total_sks'] }}</td>
                                <td class="px-5 py-3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- CPL Overview --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-1 h-6 rounded-full bg-emerald-500 inline-block"></span>
                        <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Capaian Pembelajaran Lulusan (CPL)</h2>
                    </div>
                    <a href="{{ route('public.cpl') }}" class="text-xs text-blue-700 hover:text-blue-900 font-medium transition">
                        Lihat detail →
                    </a>
                </div>
                <div class="space-y-2">
                    @foreach($cpls as $cpl)
                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-start gap-3 hover:border-blue-200 hover:bg-blue-50/30 transition-all">
                        <span class="w-10 h-10 rounded-xl bg-blue-700 text-white text-xs font-extrabold flex items-center justify-center shrink-0">
                            {{ $cpl->kode }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $cpl->deskripsi }}</p>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0 whitespace-nowrap pt-0.5">{{ $cpl->mk_count }} MK</span>
                    </div>
                    @endforeach
                </div>
            </section>

        </div>

        {{-- ── KOLOM KANAN (1/3): Quick Nav ─────────────────────── --}}
        <div class="space-y-4">

            <div class="flex items-center gap-2 mb-2">
                <span class="w-1 h-6 rounded-full bg-violet-500 inline-block"></span>
                <h2 class="text-base font-bold text-gray-900 uppercase tracking-wide">Navigasi</h2>
            </div>

            @foreach([
                [
                    'route' => 'public.profil',
                    'icon'  => 'M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z',
                    'title' => 'Profil Prodi',
                    'sub'   => 'Visi, Misi, Tujuan & Profil Lulusan',
                    'color' => 'bg-blue-50 text-blue-700 border-blue-100',
                ],
                [
                    'route' => 'public.cpl',
                    'icon'  => 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z',
                    'title' => 'CPL',
                    'sub'   => $stats['total_cpl'].' capaian lulusan',
                    'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                ],
                [
                    'route' => 'public.mata-kuliah',
                    'icon'  => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25',
                    'title' => 'Mata Kuliah',
                    'sub'   => $stats['total_mk'].' MK · '.$stats['total_sks'].' SKS',
                    'color' => 'bg-amber-50 text-amber-600 border-amber-100',
                ],
                [
                    'route' => 'public.kurikulum',
                    'icon'  => 'M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75.125a1.125 1.125 0 0 0 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m-18.75-3.75a1.125 1.125 0 0 1 1.125-1.125h1.5a1.125 1.125 0 0 1 1.125 1.125v1.5a1.125 1.125 0 0 1-1.125 1.125m-18 0h.375m-1.5 0a1.125 1.125 0 0 0 1.125 1.125M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V10.5m1.875-6h16.5A1.875 1.875 0 0 1 21.75 6.375V10.5M3.375 4.5h-.375m0 0H1.5M21.75 10.5v8.625A1.875 1.875 0 0 1 19.875 21H4.125A1.875 1.875 0 0 1 2.25 19.125V10.5m19.5 0h-19.5',
                    'title' => 'Kurikulum',
                    'sub'   => '12 tabel matriks kurikulum OBE',
                    'color' => 'bg-violet-50 text-violet-600 border-violet-100',
                ],
            ] as $nav)
            <a href="{{ route($nav['route']) }}"
               class="flex items-center gap-4 bg-white border border-gray-200 rounded-2xl p-4 hover:shadow-md hover:border-gray-300 transition-all group">
                <div class="w-11 h-11 rounded-xl {{ $nav['color'] }} border flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm group-hover:text-blue-800 transition-colors">{{ $nav['title'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $nav['sub'] }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-300 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            @endforeach

            {{-- Info Kurikulum OBE --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mt-6">
                <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-2">Tentang Kurikulum OBE</p>
                <p class="text-xs text-blue-700 leading-relaxed">
                    Kurikulum berbasis <strong>Outcome-Based Education (OBE)</strong> dan <strong>KKNI</strong> dirancang untuk memastikan setiap mata kuliah berkontribusi nyata terhadap Capaian Pembelajaran Lulusan (CPL).
                </p>
            </div>

        </div>
    </div>
</div>

@endsection
