@php
$row1 = [
    ['route' => 'kurikulum.matriks.input',  'n' => 1,  'title' => 'Input Level',        'sub' => 'Set level keterkaitan MK & CPL (Tinggi / Sedang / Rendah)'],
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
