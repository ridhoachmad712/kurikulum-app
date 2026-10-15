@extends('layouts.public')
@section('title', 'Kurikulum S1 Manajemen · FEB UNM')
@section('content')
<div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-blue-800 text-white">
    <div class="absolute -right-32 -top-40 h-96 w-96 rounded-full bg-blue-400/10 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-40 left-1/3 h-96 w-96 rounded-full bg-cyan-400/10 blur-3xl" aria-hidden="true"></div>
    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-center lg:px-8 lg:py-24">
        <div>
            <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-100"><span class="h-2 w-2 rounded-full bg-cyan-300"></span> Portal Kurikulum · FEB UNM</p>
            <h1 class="max-w-3xl text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">Kurikulum <span class="text-cyan-300">{{ $prodi->jenjang }} {{ $prodi->nama }}</span></h1>
            <p class="mt-5 text-sm font-medium text-blue-200 sm:text-base">{{ $prodi->fakultas }} · {{ $prodi->universitas }}</p>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-blue-100/90">Kenali tujuan pendidikan lulusan, capaian pembelajaran, dan mata kuliah program studi. Jelajahi daftar mata kuliah untuk melihat RPS yang telah tersedia.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('public.mata-kuliah') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-900 shadow-lg shadow-blue-950/20 transition hover:bg-cyan-50">Jelajahi Mata Kuliah &amp; RPS <span aria-hidden="true">→</span></a>
                <a href="{{ route('public.profil') }}" class="inline-flex items-center rounded-xl border border-white/30 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Kenali Profil Prodi</a>
            </div>
        </div>
        <div class="rounded-3xl border border-white/20 bg-white/10 p-6 shadow-2xl shadow-blue-950/20 backdrop-blur-sm sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-200">Sekilas kurikulum</p>
            <div class="mt-6 grid grid-cols-2 gap-4">
                @foreach([
                    [$stats['total_semesters'], 'Semester'],
                    [$stats['available_mk'], 'Mata kuliah aktif tersedia'],
                    [$stats['total_cpl'], 'CPL'],
                    [$stats['total_concentrations'], 'Jalur konsentrasi'],
                ] as $item)
                    <div class="rounded-2xl bg-white/10 p-4"><p class="text-3xl font-extrabold">{{ $item[0] }}</p><p class="mt-1 text-xs text-blue-100">{{ $item[1] }}</p></div>
                @endforeach
            </div>
            <p class="mt-5 border-t border-white/15 pt-4 text-xs leading-relaxed text-blue-100/80">Jumlah mata kuliah menghitung seluruh mata kuliah aktif, termasuk pilihan dan lintas konsentrasi.</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl space-y-16 px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
    <section aria-labelledby="peo-heading">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Arah lulusan</p>
                <h2 id="peo-heading" class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Tujuan Pendidikan Program Studi</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Program Educational Objectives (PEO) menggambarkan peran dan pencapaian yang diharapkan dari lulusan.</p>
            </div>
            <a href="{{ route('public.peo') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">Lihat semua PEO →</a>
        </div>
        @if($peos->isEmpty())
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-7 text-sm text-slate-500">PEO belum tersedia.</div>
        @else
            <div class="mt-7 grid gap-5 md:grid-cols-3">
                @foreach($peos as $peo)
                    <article class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="absolute inset-x-0 top-0 h-1 {{ ['bg-blue-600', 'bg-cyan-500', 'bg-emerald-500'][$loop->index % 3] }}"></div>
                        <span class="inline-flex rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">{{ $peo->kode }}</span>
                        <h3 class="mt-5 text-lg font-bold leading-snug text-slate-900">{{ $peo->nama }}</h3>
                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $peo->deskripsi }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section aria-labelledby="overview-heading" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Kurikulum sekilas</p>
                <h2 id="overview-heading" class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Pahami pilihan studi</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">Daftar publik memuat <strong class="text-slate-900">{{ $stats['available_mk'] }} mata kuliah aktif</strong> dengan total {{ $stats['available_sks'] }} SKS yang tersedia di semua semester dan konsentrasi. Mahasiswa mengambil mata kuliah sesuai jalur dan pilihan yang berlaku.</p>
                @if($concentrations->isNotEmpty())
                    <p class="mt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">Jalur konsentrasi</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach($concentrations as $concentration)
                            <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-800">{{ $concentration }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-5"><p class="text-3xl font-extrabold text-slate-900">{{ $stats['available_mk'] }}</p><p class="mt-1 text-sm font-semibold text-slate-700">Mata kuliah tersedia</p><p class="mt-1 text-xs text-slate-500">Seluruh mata kuliah aktif</p></div>
                <div class="rounded-2xl bg-blue-50 p-5"><p class="text-3xl font-extrabold text-blue-800">{{ $stats['available_sks'] }}</p><p class="mt-1 text-sm font-semibold text-slate-700">SKS tersedia</p><p class="mt-1 text-xs text-slate-500">Akumulasi seluruh mata kuliah aktif</p></div>
                <div class="rounded-2xl bg-cyan-50 p-5"><p class="text-3xl font-extrabold text-cyan-800">{{ $stats['total_sks'] }}</p><p class="mt-1 text-sm font-semibold text-slate-700">Perkiraan SKS per jalur</p><p class="mt-1 text-xs text-slate-500">Bergantung konsentrasi dan mata kuliah pilihan</p></div>
                <div class="rounded-2xl bg-emerald-50 p-5"><p class="text-3xl font-extrabold text-emerald-800">{{ $stats['total_cpl'] }}</p><p class="mt-1 text-sm font-semibold text-slate-700">CPL program studi</p><p class="mt-1 text-xs text-slate-500">Capaian pembelajaran lulusan</p></div>
            </div>
        </div>
    </section>

    <section aria-labelledby="explore-heading">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Jelajahi lebih lanjut</p>
        <h2 id="explore-heading" class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Temukan informasi yang Anda butuhkan</h2>
        <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['route' => 'public.mata-kuliah', 'number' => '01', 'title' => 'Mata Kuliah & RPS', 'description' => 'Cari mata kuliah per semester, baca detail, dan buka RPS yang sudah diunggah.'],
                ['route' => 'public.cpl', 'number' => '02', 'title' => 'Capaian Lulusan', 'description' => 'Lihat CPL dan kaitannya dengan mata kuliah.'],
                ['route' => 'public.bahan-kajian', 'number' => '03', 'title' => 'Bahan Kajian', 'description' => 'Telusuri bidang pengetahuan yang mendasari kurikulum.'],
                ['route' => 'public.kurikulum', 'number' => '04', 'title' => 'Matriks Kurikulum', 'description' => 'Jelajahi pemetaan lengkap pada 12 tabel kurikulum.'],
            ] as $link)
                <a href="{{ route($link['route']) }}" class="group flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                    <span class="text-xs font-extrabold text-blue-600">{{ $link['number'] }}</span>
                    <h3 class="mt-5 text-base font-bold text-slate-900 group-hover:text-blue-700">{{ $link['title'] }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $link['description'] }}</p>
                    <span class="mt-5 text-sm font-bold text-blue-700">Buka halaman →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section aria-labelledby="semester-heading" class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
        <div>
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">Sebaran studi</p><h2 id="semester-heading" class="mt-2 text-2xl font-extrabold text-slate-900">Mata kuliah per semester</h2></div>
                <a href="{{ route('public.mata-kuliah') }}" class="text-sm font-bold text-blue-700 hover:text-blue-900">Lihat daftar lengkap →</a>
            </div>
            <p class="mt-2 text-sm text-slate-600">Angka berikut menghitung seluruh mata kuliah aktif yang tersedia pada setiap semester.</p>
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach($bySemester as $semester => $data)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4">
                        <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-extrabold text-blue-800">{{ $semester }}</span><div><p class="text-sm font-bold text-slate-900">Semester {{ $semester }}</p><p class="text-xs text-slate-500">{{ $data['count'] }} mata kuliah tersedia</p></div></div>
                        <span class="text-sm font-bold text-blue-800">{{ $data['sks'] }} SKS</span>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs leading-relaxed text-slate-500">Total semester mencakup mata kuliah dari semua konsentrasi dan pilihan. Beban studi tiap mahasiswa dapat berbeda.</p>
        </div>
        <div class="rounded-3xl bg-slate-900 p-6 text-white sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">Capaian lulusan</p>
            <h2 class="mt-3 text-2xl font-extrabold">Dari tujuan ke pembelajaran</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-300">PEO menjelaskan arah pencapaian lulusan. CPL merinci kemampuan yang dibangun selama studi, lalu dihubungkan dengan mata kuliah dan CPMK.</p>
            <div class="mt-7 space-y-3">
                @foreach($cpls->take(3) as $cpl)
                    <div class="rounded-xl border border-white/10 bg-white/5 p-3"><span class="text-xs font-extrabold text-cyan-300">{{ $cpl->kode }}</span><p class="mt-1 text-xs leading-relaxed text-slate-200">{{ $cpl->deskripsi }}</p></div>
                @endforeach
            </div>
            <a href="{{ route('public.cpl') }}" class="mt-6 inline-flex text-sm font-bold text-cyan-200 hover:text-white">Pelajari semua CPL →</a>
        </div>
    </section>
</div>
@endsection
