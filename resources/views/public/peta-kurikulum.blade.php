@extends('layouts.public')
@section('title', 'Peta Kurikulum · ' . $prodi->jenjang . ' ' . $prodi->nama)
@section('content')
<div class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <nav aria-label="Jejak halaman" class="mb-4 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('public.home') }}" class="hover:text-blue-700">Beranda</a>
            <span aria-hidden="true">›</span>
            <span class="font-semibold text-slate-800">Peta Kurikulum</span>
        </nav>
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-blue-700">{{ $prodi->fakultas }} · {{ $prodi->universitas }}</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Peta Kurikulum Program Studi</h1>
                <p class="mt-1 text-lg font-semibold text-slate-700">{{ $prodi->jenjang }} {{ $prodi->nama }}</p>
                <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600">
                    Jelajahi susunan mata kuliah {{ $prodi->jenjang }} {{ $prodi->nama }} menurut semester.
                    Pilih kartu untuk melihat CPL, dosen, dan RPS yang tersedia.
                </p>
            </div>
            <a href="{{ route('public.mata-kuliah') }}" class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-400 hover:text-blue-700">Lihat daftar mata kuliah →</a>
        </div>
    </div>
</div>

<div class="curriculum-map mx-auto max-w-[1760px] px-4 py-8 sm:px-6 lg:px-8" x-data="curriculumMap(@js($courses), @js($concentrations))" @keydown.escape.window="close()">
    <noscript><p class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Peta interaktif memerlukan JavaScript. Anda tetap dapat melihat seluruh mata kuliah melalui <a href="{{ route('public.mata-kuliah') }}" class="font-bold underline">daftar mata kuliah</a>.</p></noscript>
    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm leading-relaxed text-blue-900">
        <strong>Petunjuk:</strong> urutan kolom menunjukkan semester penempatan mata kuliah. Tidak ada garis prasyarat karena relasi prasyarat belum dicatat dalam aplikasi.
        Pilih satu konsentrasi untuk melihat mata kuliah umum bersama mata kuliah pada jalur tersebut.
    </div>

    <section aria-label="Filter peta kurikulum" class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <label class="block">
                <span class="mb-1.5 block text-xs font-bold text-slate-700">Cari mata kuliah</span>
                <input type="search" x-model.debounce.200ms="search" placeholder="Nama atau kode..." class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </label>
            <label class="block">
                <span class="mb-1.5 block text-xs font-bold text-slate-700">Konsentrasi</span>
                <select x-model="concentration" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua konsentrasi</option>
                    @foreach($concentrations as $concentration)
                        <option value="{{ $concentration }}">{{ $concentration }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block">
                <span class="mb-1.5 block text-xs font-bold text-slate-700">Jenis mata kuliah</span>
                <select x-model="type" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua jenis</option>
                    <option value="common">Umum</option>
                    <option value="concentration">Konsentrasi</option>
                    <option value="elective">Pilihan</option>
                </select>
            </label>
            <label class="block">
                <span class="mb-1.5 block text-xs font-bold text-slate-700">Sorot CPL terkait</span>
                <select x-model="cpl" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua CPL</option>
                    @foreach($cpls as $cplItem)
                        <option value="{{ $cplItem->id }}">{{ $cplItem->kode }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <p class="text-sm text-slate-600" aria-live="polite">
                <strong class="text-slate-900" x-text="filteredCourses.length"></strong> mata kuliah aktif tersedia ·
                <strong class="text-slate-900" x-text="filteredSks"></strong> SKS tersedia
                <span x-show="cpl" x-cloak> · <strong class="text-emerald-700" x-text="highlightedCount"></strong> terkait CPL terpilih</span>
            </p>
            <button type="button" @click="reset()" class="text-sm font-bold text-blue-700 hover:text-blue-900">Atur ulang filter</button>
        </div>
    </section>

    <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-600" aria-label="Legenda jenis mata kuliah">
        <span class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm bg-rose-100 ring-1 ring-rose-400"></span> Umum</span>
        @foreach($concentrations as $concentration)
            <span class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm {{ ['bg-sky-100 ring-sky-400', 'bg-amber-100 ring-amber-400', 'bg-lime-100 ring-lime-500', 'bg-violet-100 ring-violet-400', 'bg-teal-100 ring-teal-400'][$loop->index % 5] }} ring-1"></span> {{ $concentration }}</span>
        @endforeach
        <span class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm border border-dashed border-slate-600 bg-white"></span> Pilihan (garis putus)</span>
        <span class="text-slate-400">Angka SKS mencakup semua mata kuliah yang terlihat, bukan beban studi satu mahasiswa.</span>
    </div>

    <div x-show="filteredCourses.length === 0" x-cloak class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
        Tidak ada mata kuliah yang cocok dengan filter. <button type="button" @click="reset()" class="font-bold text-blue-700 underline">Tampilkan semua</button>
    </div>

    <div class="mt-6 hidden items-center justify-between gap-4 md:flex">
        <p class="text-xs font-medium text-slate-500">Geser untuk menjelajahi semester berikutnya <span aria-hidden="true">→</span></p>
        <div class="flex gap-2" aria-label="Navigasi semester">
            <button type="button" @click="scrollMap(-1)" aria-label="Geser ke semester sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-lg text-slate-700 shadow-sm transition hover:-translate-x-0.5 hover:border-blue-400 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">←</button>
            <button type="button" @click="scrollMap(1)" aria-label="Geser ke semester berikutnya" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-lg text-slate-700 shadow-sm transition hover:translate-x-0.5 hover:border-blue-400 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">→</button>
        </div>
    </div>

    {{-- Desktop: seluruh semester terlihat sebagai jalur horizontal. --}}
    <section x-ref="desktopMap" aria-label="Peta mata kuliah per semester" class="mt-3 hidden overflow-x-auto rounded-xl border border-slate-300 bg-slate-200 p-3 md:block">
        <div class="w-max min-w-full">
        <div class="border-b border-slate-400 pb-1 text-center text-xs font-extrabold uppercase tracking-[0.22em] text-slate-700">Semester</div>
        <div class="flex gap-1 pt-1 pb-2">
            @foreach($semesters as $semester)
                <div class="w-48 shrink-0 border-r border-slate-300 px-1.5 last:border-r-0">
                    <div class="mb-2 border-b border-slate-400 px-1 py-1.5 text-center text-slate-900">
                        <p class="text-base font-extrabold leading-none">{{ $semester ? (['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII'][$semester - 1] ?? $semester) : '—' }}</p>
                        <p class="mt-1 text-[10px] font-medium text-slate-600"><span x-text="inSemester({{ $semester }}).length"></span> MK · <span x-text="inSemester({{ $semester }}).reduce((sum, course) => sum + Number(course.sks), 0)"></span> SKS</p>
                    </div>
                    <div class="space-y-1">
                        <template x-for="(course, index) in inSemester({{ $semester }})" :key="course.id">
                            <button type="button" @click="select(course)" :title="course.name + ' · ' + course.code" :aria-label="course.name + ', ' + course.sks + ' SKS, ' + courseType(course)" :style="{ '--card-index': index }" :class="[courseClass(course), cpl && !highlights(course) ? 'opacity-40' : '', cpl && highlights(course) ? 'ring-2 ring-emerald-600' : '']" class="map-card map-strip group flex min-h-8 w-full items-center justify-between gap-1 rounded-[5px] border px-1.5 py-1 text-left focus:outline-none focus:ring-2 focus:ring-blue-700">
                                <span class="map-strip-title text-[11px] font-semibold leading-tight text-slate-900" x-text="course.name"></span>
                                <span class="shrink-0 text-[10px] font-bold text-slate-700" x-text="course.sks + ' SKS'"></span>
                                <span x-show="course.elective" class="sr-only">Pilihan</span>
                            </button>
                        </template>
                        <p x-show="inSemester({{ $semester }}).length === 0" class="px-2 py-4 text-center text-xs text-slate-400">Tidak ada mata kuliah</p>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </section>

    {{-- Ponsel: satu semester terbuka pada satu waktu. --}}
    <section aria-label="Peta mata kuliah per semester" class="mt-7 space-y-2 md:hidden">
        @foreach($semesters as $semester)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <button type="button" @click="expandedSemester = expandedSemester === {{ $semester }} ? null : {{ $semester }}" :aria-expanded="(expandedSemester === {{ $semester }}).toString()" class="flex w-full items-center justify-between gap-3 px-4 py-4 text-left">
                    <span><span class="block text-sm font-bold text-slate-900">{{ $semester ? 'Semester ' . $semester : 'Belum ditempatkan' }}</span><span class="text-xs text-slate-500"><span x-text="inSemester({{ $semester }}).length"></span> mata kuliah · <span x-text="inSemester({{ $semester }}).reduce((sum, course) => sum + Number(course.sks), 0)"></span> SKS</span></span>
                    <span aria-hidden="true" class="text-lg text-blue-700" x-text="expandedSemester === {{ $semester }} ? '−' : '+'"></span>
                </button>
                <div x-show="expandedSemester === {{ $semester }}" x-transition.opacity.duration.200ms x-cloak class="space-y-1 border-t border-slate-200 bg-slate-200 p-2.5">
                    <template x-for="(course, index) in inSemester({{ $semester }})" :key="course.id">
                        <button type="button" @click="select(course)" :title="course.name + ' · ' + course.code" :aria-label="course.name + ', ' + course.sks + ' SKS, ' + courseType(course)" :style="{ '--card-index': index }" :class="[courseClass(course), cpl && !highlights(course) ? 'opacity-40' : '', cpl && highlights(course) ? 'ring-2 ring-emerald-600' : '']" class="map-card map-strip flex min-h-9 w-full items-center justify-between gap-2 rounded-[5px] border px-2 py-1.5 text-left">
                            <span class="map-strip-title text-xs font-semibold leading-tight text-slate-900" x-text="course.name"></span>
                            <span class="shrink-0 text-[10px] font-bold text-slate-700" x-text="course.sks + ' SKS'"></span>
                            <span x-show="course.elective" class="sr-only">Pilihan</span>
                        </button>
                    </template>
                    <p x-show="inSemester({{ $semester }}).length === 0" class="py-4 text-center text-xs text-slate-400">Tidak ada mata kuliah</p>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Panel detail dibuka dari kartu; semua teks data dirender sebagai teks, bukan HTML. --}}
    <div x-show="selected" x-transition.opacity.duration.200ms x-cloak class="fixed inset-0 z-[60] flex justify-end bg-slate-950/50" @click.self="close()" role="presentation">
        <div x-show="selected" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" role="dialog" aria-modal="true" aria-labelledby="map-detail-title" class="flex h-full w-full max-w-lg flex-col overflow-y-auto bg-white shadow-2xl" @click.stop>
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-7">
                <div><p class="text-xs font-bold uppercase tracking-wider text-blue-700">Detail mata kuliah</p><h2 id="map-detail-title" class="mt-2 text-xl font-extrabold text-slate-900" x-text="selected?.name"></h2><p class="mt-1 text-xs text-slate-500" x-text="selected?.code"></p></div>
                <button type="button" x-ref="detailClose" @click="close()" aria-label="Tutup detail" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">✕</button>
            </div>
            <div class="flex-1 space-y-6 px-5 py-6 sm:px-7">
                <div class="flex flex-wrap gap-2 text-xs font-bold">
                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-blue-800" x-text="selected?.semester ? 'Semester ' + selected.semester : 'Semester belum ditetapkan'"></span>
                    <span class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-700" x-text="selected?.sks + ' SKS'"></span>
                    <span class="rounded-full bg-amber-50 px-3 py-1.5 text-amber-800" x-text="selected ? courseType(selected) : ''"></span>
                </div>
                <div x-show="selected?.concentration"><h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Konsentrasi</h3><p class="mt-2 text-sm text-slate-800" x-text="selected?.concentration"></p></div>
                <div><h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi</h3><p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700" x-text="selected?.description || 'Deskripsi belum tersedia.'"></p></div>
                <div><h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">CPL terkait</h3><div class="mt-2 flex flex-wrap gap-2"><template x-for="item in (selected?.cpls || [])" :key="item.id"><span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-800" x-text="item.code + (item.level ? ' · ' + item.level : '')"></span></template><span x-show="!selected?.cpls?.length" class="text-sm text-slate-500">Belum dipetakan.</span></div></div>
                <div><h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Dosen pengampu</h3><p class="mt-2 text-sm text-slate-700" x-text="selected?.lecturers?.length ? selected.lecturers.join(', ') : 'Belum ditetapkan.'"></p></div>
            </div>
            <div class="flex flex-wrap gap-2 border-t border-slate-200 bg-slate-50 px-5 py-5 sm:px-7">
                <a :href="selected?.detailUrl" class="inline-flex rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Lihat detail mata kuliah</a>
                <a x-show="selected?.hasRps" :href="selected?.rpsUrl" target="_blank" rel="noopener" class="inline-flex rounded-xl border border-blue-300 bg-white px-4 py-2.5 text-sm font-bold text-blue-800 hover:bg-blue-50">Buka RPS</a>
                <span x-show="selected && !selected.hasRps" class="inline-flex rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-bold text-slate-500" aria-disabled="true">RPS belum tersedia</span>
            </div>
        </div>
    </div>
</div>
@endsection
