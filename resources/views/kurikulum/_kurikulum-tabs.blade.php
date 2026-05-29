@php
$row1 = [
    ['route' => 'kurikulum.matriks.tabel1', 'n' => 1,  'title' => 'Input Level',        'sub' => 'Level keterkaitan MK & CPL (Tinggi / Sedang / Rendah)'],
    ['route' => 'kurikulum.matriks.tabel2', 'n' => 2,  'title' => 'Keterkaitan (0/1)',  'sub' => 'Matriks biner — ada (1) atau tidak (0) keterkaitan MK terhadap CPL'],
    ['route' => 'kurikulum.matriks.tabel3', 'n' => 3,  'title' => 'Nilai Level',        'sub' => 'Nilai numerik dari level keterkaitan MK terhadap CPL'],
    ['route' => 'kurikulum.matriks.tabel4', 'n' => 4,  'title' => '% Bobot Global',     'sub' => 'Persentase bobot MK terhadap CPL secara keseluruhan'],
    ['route' => 'kurikulum.matriks.tabel5', 'n' => 5,  'title' => '% Bobot per MK',    'sub' => 'Persentase bobot keterkaitan yang dinormalisasi per mata kuliah'],
];

$row2 = [
    ['route' => 'kurikulum.cpmk.tabel6',      'n' => 6,  'title' => 'CPMK × CPL',        'sub' => 'Keterkaitan CPMK dengan CPL yang dibebankan (V / —)'],
    ['route' => 'kurikulum.cpmk.tabel7',      'n' => 7,  'title' => 'Bobot CPMK × CPL',  'sub' => 'Bobot persentase CPMK terhadap setiap CPL yang didukung'],
    ['route' => 'kurikulum.sub-cpmk.tabel8',  'n' => 8,  'title' => 'Rekap Sub-CPMK',    'sub' => 'Rekapitulasi Sub-CPMK beserta CLO Taxonomy tertinggi'],
    ['route' => 'kurikulum.sub-cpmk.tabel9',  'n' => 9,  'title' => 'CPMK × Sub',        'sub' => 'Matriks keterkaitan CPMK dengan Sub-CPMK yang dibebankan'],
    ['route' => 'kurikulum.sub-cpmk.tabel10', 'n' => 10, 'title' => 'Sub × CPL',         'sub' => 'Keterkaitan Sub-CPMK dengan CPL (diwarisi dari CPMK induk)'],
    ['route' => 'kurikulum.cpmk.tabel11',     'n' => 11, 'title' => '% Bobot CPMK',      'sub' => 'Distribusi % bobot tiap Sub-CPMK dalam CPMK & perbandingan Tabel 7'],
    ['route' => 'kurikulum.cpmk.tabel12',     'n' => 12, 'title' => '% Matriks CPMK×CPL','sub' => 'Persentase bobot CPMK terhadap CPL beserta total kontribusi per CPL'],
];

$activeRoute = request()->route()?->getName() ?? '';
$activeInfo  = null;
foreach (array_merge($row1, $row2) as $tab) {
    if ($tab['route'] === $activeRoute) { $activeInfo = $tab; break; }
}
@endphp

{{-- ── TAB BAR ─────────────────────────────────────────────────── --}}
<div class="-mx-4 sm:-mx-6 px-4 sm:px-6 mb-6 space-y-1.5">

    {{-- Baris 1: Tabel 1–5 --}}
    <div class="flex items-center gap-1 bg-gray-100 rounded-2xl p-1">
        @foreach($row1 as $tab)
        @php $isActive = $tab['route'] === $activeRoute; @endphp
        <a href="{{ route($tab['route']) }}"
           class="flex flex-1 items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-medium
                  whitespace-nowrap transition-all duration-150
                  {{ $isActive
                      ? 'bg-white shadow-sm text-gray-800 font-semibold'
                      : 'text-gray-500 hover:text-gray-700 hover:bg-white/60' }}">
            <span class="w-5 h-5 rounded-md text-[10px] font-bold flex items-center justify-center shrink-0
                         {{ $isActive ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-500' }}">
                {{ $tab['n'] }}
            </span>
            {{ $tab['title'] }}
        </a>
        @endforeach
    </div>

    {{-- Baris 2: Tabel 6–12 --}}
    <div class="flex items-center gap-1 bg-gray-100 rounded-2xl p-1 overflow-x-auto scrollbar-none">
        @foreach($row2 as $tab)
        @php $isActive = $tab['route'] === $activeRoute; @endphp
        <a href="{{ route($tab['route']) }}"
           class="flex flex-1 items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-medium
                  whitespace-nowrap transition-all duration-150
                  {{ $isActive
                      ? 'bg-white shadow-sm text-gray-800 font-semibold'
                      : 'text-gray-500 hover:text-gray-700 hover:bg-white/60' }}">
            <span class="w-5 h-5 rounded-md text-[10px] font-bold flex items-center justify-center shrink-0
                         {{ $isActive ? 'bg-gray-700 text-white' : 'bg-gray-200 text-gray-500' }}">
                {{ $tab['n'] }}
            </span>
            {{ $tab['title'] }}
        </a>
        @endforeach
    </div>

</div>

{{-- Deskripsi tab aktif --}}
@if($activeInfo)
<p class="text-xs text-gray-400 -mt-3 mb-5">
    <span class="inline-flex items-center gap-1.5">
        <span class="w-4 h-4 rounded bg-gray-700 text-white text-[9px] font-bold flex items-center justify-center">
            {{ $activeInfo['n'] }}
        </span>
        <span class="font-semibold text-gray-600">{{ $activeInfo['title'] }}</span>
    </span>
    <span class="mx-1.5 text-gray-200">|</span>
    {{ $activeInfo['sub'] }}
</p>
@endif

@section('help_title', 'Informasi Tabel Kurikulum')

@section('help')
<div class="space-y-4">

    {{-- Tabel aktif saat ini --}}
    @if($activeInfo)
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
        <p class="text-xs text-blue-700 font-semibold mb-0.5">
            Tabel {{ $activeInfo['n'] }} — {{ $activeInfo['title'] }}
        </p>
        <p class="text-xs text-blue-600 leading-relaxed">{{ $activeInfo['sub'] }}</p>
    </div>
    @endif

    {{-- Baris 1: Tabel 1–5 --}}
    <div>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tabel 1–5 · Matriks Level MK × CPL</p>
        <div class="space-y-2">
            @foreach([
                [1, 'Input Level',       'Menampilkan tingkat keterkaitan setiap MK terhadap CPL: Tinggi, Sedang, atau Rendah.'],
                [2, 'Keterkaitan (0/1)', 'Konversi level menjadi matriks biner — ada keterkaitan (1) atau tidak (0).'],
                [3, 'Nilai Level',       'Konversi level ke nilai numerik: Tinggi = 3, Sedang = 2, Rendah = 1.'],
                [4, '% Bobot Global',    'Persentase kontribusi bobot setiap MK terhadap CPL secara keseluruhan.'],
                [5, '% Bobot per MK',   'Bobot keterkaitan yang dinormalisasi per mata kuliah (total per baris = 100%).'],
            ] as [$n, $title, $desc])
            <div class="flex items-start gap-2.5 text-xs">
                <span class="w-5 h-5 rounded bg-gray-700 text-white text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</span>
                <div>
                    <span class="font-semibold text-gray-700">{{ $title }}</span>
                    <span class="text-gray-400 ml-1">— {{ $desc }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Baris 2: Tabel 6–12 --}}
    <div>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tabel 6–12 · CPMK, Sub-CPMK & Bobot</p>
        <div class="space-y-2">
            @foreach([
                [6,  'CPMK × CPL',         'Keterkaitan setiap CPMK dengan CPL yang dibebankan (V = terkait / — = tidak).'],
                [7,  'Bobot CPMK × CPL',   'Bobot persentase CPMK terhadap CPL yang didukung (input manual oleh dosen).'],
                [8,  'Rekap Sub-CPMK',     'Rekapitulasi seluruh Sub-CPMK beserta CLO Taxonomy tertinggi yang dicapai.'],
                [9,  'CPMK × Sub',         'Matriks keterkaitan antara CPMK dengan Sub-CPMK yang dibebankan di bawahnya.'],
                [10, 'Sub × CPL',          'Keterkaitan Sub-CPMK dengan CPL yang diwarisi dari CPMK induknya.'],
                [11, '% Bobot CPMK',       'Distribusi % bobot tiap Sub-CPMK dalam CPMK-nya & perbandingan dengan Tabel 7.'],
                [12, '% Matriks CPMK×CPL', 'Persentase bobot CPMK terhadap CPL beserta total kontribusi per kolom CPL.'],
            ] as [$n, $title, $desc])
            <div class="flex items-start gap-2.5 text-xs">
                <span class="w-5 h-5 rounded bg-gray-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</span>
                <div>
                    <span class="font-semibold text-gray-700">{{ $title }}</span>
                    <span class="text-gray-400 ml-1">— {{ $desc }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-amber-50 rounded-xl px-4 py-3">
        <p class="text-xs text-amber-700 font-medium">💡 Tips</p>
        <p class="text-xs text-amber-600 mt-1 leading-relaxed">Tabel 1–5 dihasilkan otomatis dari input level di menu <strong>Level CPL Mata Kuliah</strong>. Tabel 6–12 bergantung pada isian CPMK, Sub-CPMK, dan bobot yang diisi oleh dosen.</p>
    </div>
</div>
@endsection

{{-- Banner dosen scope — tampil di semua tabel kecuali halaman input (punya bannernya sendiri) --}}
@if($activeRoute !== 'kurikulum.matriks.input')
@hasrole('dosen')
<div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda.</span>
</div>
@endhasrole
@endif
