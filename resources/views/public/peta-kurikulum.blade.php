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
                    Pilih konsentrasi, lalu telusuri mata kuliah dari semester awal hingga akhir.
                    Sorot mata kuliah atau CPL untuk melihat hubungan yang telah dipetakan.
                </p>
            </div>
            <a href="{{ route('public.mata-kuliah') }}" class="inline-flex rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-blue-400 hover:text-blue-700">Lihat daftar mata kuliah →</a>
        </div>
    </div>
</div>

<div class="curriculum-map mx-auto max-w-[1760px] px-4 py-8 sm:px-6 lg:px-8" x-data="curriculumMap(@js($courses), @js($concentrations), @js($cpls))" @keydown.escape.window="close()">
    <noscript><p class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Peta interaktif memerlukan JavaScript. Anda tetap dapat melihat seluruh mata kuliah melalui <a href="{{ route('public.mata-kuliah') }}" class="font-bold underline">daftar mata kuliah</a>.</p></noscript>
    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm leading-relaxed text-blue-900">
        <strong>Petunjuk:</strong> setiap peta menampilkan mata kuliah umum dan satu konsentrasi. Garis berwarna menunjukkan hubungan mata kuliah–CPL yang tercatat, bukan prasyarat. Garis muncul saat kartu atau CPL disorot.
    </div>

    <section aria-label="Pilih konsentrasi" class="mt-6">
        <h2 class="text-sm font-bold text-slate-900">Peta berdasarkan konsentrasi</h2>
        <div class="mt-3 flex flex-wrap gap-2" role="group" aria-label="Konsentrasi">
            @forelse($concentrations as $concentration)
                <button type="button" @click="selectConcentration(@js($concentration))" :aria-pressed="(concentration === @js($concentration)).toString()" :class="concentration === @js($concentration) ? 'border-blue-700 bg-blue-700 text-white shadow-md' : 'border-slate-300 bg-white text-slate-700 hover:border-blue-400 hover:text-blue-700'" class="rounded-xl border px-4 py-2.5 text-sm font-bold transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    {{ $concentration }}
                </button>
            @empty
                <span class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600">Mata kuliah umum</span>
            @endforelse
        </div>
    </section>

    <section aria-label="Filter peta kurikulum" class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block">
                <span class="mb-1.5 block text-xs font-bold text-slate-700">Cari mata kuliah</span>
                <input type="search" x-model.debounce.200ms="search" placeholder="Nama atau kode..." class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
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
        </div>
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <p class="text-sm text-slate-600" aria-live="polite">
                <strong class="text-slate-900" x-text="filteredCourses.length"></strong> mata kuliah tersedia pada jalur ini ·
                <strong class="text-slate-900" x-text="filteredSks"></strong> SKS seluruh mata kuliah yang tampil
            </p>
            <button type="button" @click="reset()" class="text-sm font-bold text-blue-700 hover:text-blue-900">Atur ulang filter</button>
        </div>
    </section>

    <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-slate-600" aria-label="Legenda jenis mata kuliah">
        <span class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm bg-rose-100 ring-1 ring-rose-400"></span> Umum</span>
        @foreach($concentrations as $concentration)
            <span x-show="concentration === @js($concentration)" class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm {{ ['bg-sky-100 ring-sky-400', 'bg-amber-100 ring-amber-400', 'bg-lime-100 ring-lime-500', 'bg-violet-100 ring-violet-400', 'bg-teal-100 ring-teal-400'][$loop->index % 5] }} ring-1"></span> {{ $concentration }}</span>
        @endforeach
        <span class="inline-flex items-center gap-2"><span class="h-3 w-5 rounded-sm border border-dashed border-slate-600 bg-white"></span> Pilihan (garis putus)</span>
        <span class="text-slate-400">SKS tersedia bukan beban studi satu mahasiswa.</span>
    </div>

    <div x-show="filteredCourses.length === 0" x-cloak class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
        Tidak ada mata kuliah yang cocok dengan filter. <button type="button" @click="reset()" class="font-bold text-blue-700 underline">Tampilkan semua</button>
    </div>

    <section x-ref="diagram" aria-label="Peta hubungan mata kuliah dan CPL" class="relative mt-6 overflow-hidden rounded-2xl border border-slate-300 bg-slate-50 p-4 sm:p-5">
        <svg aria-hidden="true" :width="svgSize.width" :height="svgSize.height" class="pointer-events-none absolute left-0 top-0 z-20 hidden md:block">
            <template x-for="path in linePaths" :key="path.key">
                <path :d="path.d" :stroke="path.color" stroke-width="2.5" fill="none" stroke-linecap="round" class="map-link"></path>
            </template>
        </svg>

        <div class="relative z-30 rounded-xl border border-slate-200 bg-white/95 p-4 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Capaian Pembelajaran Lulusan</h2>
                    <p class="mt-1 text-xs text-slate-500">Arahkan kursor atau pilih CPL untuk menyorot mata kuliah yang terkait.</p>
                </div>
                <button x-show="activeCpl" x-cloak type="button" @click="activeCpl = null; hoveredCpl = null; schedulePaths()" class="text-xs font-bold text-blue-700 hover:underline">Hapus sorotan CPL</button>
            </div>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($cpls as $cplItem)
                    <button type="button" data-map-cpl="{{ $cplItem->id }}" @mouseenter="hoverCpl({{ $cplItem->id }})" @mouseleave="leaveCpl()" @focus="hoverCpl({{ $cplItem->id }})" @blur="leaveCpl()" @click="toggleCpl({{ $cplItem->id }})" :class="focusedCourse ? (relatedToCpl(focusedCourse, {{ $cplItem->id }}) ? 'ring-2 ring-offset-2 opacity-100' : 'opacity-40') : (String(focusedCpl) === '{{ $cplItem->id }}' ? 'ring-2 ring-offset-2 opacity-100' : 'opacity-100')" :style="{ '--tw-ring-color': cplColor({{ $cplItem->id }}) }" :aria-pressed="(String(activeCpl) === '{{ $cplItem->id }}').toString()" title="{{ $cplItem->deskripsi }}" class="relative rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2">
                        <span class="mr-1.5 inline-block h-2.5 w-2.5 rounded-full" :style="{ backgroundColor: cplColor({{ $cplItem->id }}) }"></span>{{ $cplItem->kode }}
                    </button>
                @endforeach
            </div>
            <p class="mt-3 min-h-5 text-xs text-slate-600" aria-live="polite">
                <span x-show="focusedCourse" x-cloak><strong x-text="focusedCourse?.name"></strong> terhubung ke <strong x-text="focusedCourse?.cpls?.length || 0"></strong> CPL.</span>
                <span x-show="!focusedCourse && focusedCpl" x-cloak><strong x-text="relatedCount"></strong> mata kuliah pada jalur ini terkait dengan CPL yang disorot.</span>
                <span x-show="!focusedCourse && !focusedCpl">Pilih satu mata kuliah atau CPL untuk melihat hubungannya.</span>
            </p>
        </div>

        <div class="relative mt-4 space-y-3" @mouseleave="hidePreview()">
            @foreach(collect($semesters)->sortDesc() as $semester)
                <div class="grid gap-3 rounded-xl border border-slate-200 bg-white/95 p-3 shadow-sm md:grid-cols-[112px_minmax(0,1fr)]">
                    <div class="relative z-30 flex items-center justify-between gap-2 border-b border-slate-100 pb-2 md:block md:border-b-0 md:border-r md:pb-0 md:pr-3">
                        <h3 class="text-sm font-extrabold text-slate-900">{{ $semester ? 'Semester ' . $semester : 'Belum ditempatkan' }}</h3>
                        <p class="text-[11px] text-slate-500" x-text="semesterSummary({{ $semester }})"></p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                        <template x-for="(course, index) in inSemester({{ $semester }})" :key="course.id">
                            <button type="button" :data-map-course="course.id" @click="select(course)" @mouseenter="showPreview($event, course)" @mousemove="movePreview($event)" @mouseleave="hidePreview()" @focus="showPreview($event, course)" @blur="hidePreview()" :aria-label="course.name + ', ' + course.sks + ' SKS, ' + courseType(course)" :style="{ '--card-index': index }" :class="[courseClass(course), isDimmed(course) ? 'opacity-35' : 'opacity-100', focusedCourse?.id === course.id || (focusedCpl && relatedToCpl(course)) ? 'ring-2 ring-blue-700' : '']" class="map-card relative z-30 min-h-16 rounded-lg border px-3 py-2.5 text-left focus:outline-none focus:ring-2 focus:ring-blue-700">
                                <span class="block text-[10px] font-bold uppercase tracking-wide text-slate-600" x-text="course.code"></span>
                                <span class="mt-1 block text-xs font-bold leading-snug text-slate-900" x-text="course.name"></span>
                                <span class="mt-2 block text-[11px] text-slate-700" x-text="course.sks + ' SKS · ' + courseType(course)"></span>
                            </button>
                        </template>
                        <p x-show="inSemester({{ $semester }}).length === 0" class="col-span-full py-4 text-center text-xs text-slate-400">Tidak ada mata kuliah yang cocok.</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div x-show="hovered && !selected" x-transition.opacity.scale.95.duration.150ms x-cloak :style="{ left: previewX + 'px', top: previewY + 'px' }" class="pointer-events-none fixed z-[55] hidden w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-2xl md:block" role="status">
        <p class="text-[10px] font-bold uppercase tracking-wide text-blue-700" x-text="hovered?.code"></p>
        <p class="mt-1 text-sm font-bold leading-snug text-slate-900" x-text="hovered?.name"></p>
        <p class="mt-2 text-xs text-slate-600" x-text="hovered ? 'Semester ' + hovered.semester + ' · ' + hovered.sks + ' SKS · ' + courseType(hovered) : ''"></p>
        <p x-show="hovered?.concentration" class="mt-1 text-xs font-semibold text-blue-800" x-text="hovered?.concentration"></p>
        <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-slate-700" x-text="hovered?.description || 'Deskripsi belum tersedia.'"></p>
        <p class="mt-2 text-[11px] text-slate-500" x-text="hovered?.cpls?.length ? 'CPL: ' + hovered.cpls.map(item => item.code).join(', ') : 'CPL belum dipetakan'"></p>
        <p class="mt-3 border-t border-slate-100 pt-2 text-[10px] font-semibold text-blue-700">Klik untuk detail lengkap</p>
    </div>

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
