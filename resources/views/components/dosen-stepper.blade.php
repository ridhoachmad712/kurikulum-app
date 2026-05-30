@props(['step', 'mataKuliah' => null])

@if(auth()->user()?->hasRole('dosen'))
@php
$mk = $mataKuliah;

$steps = [
    1 => ['label' => 'Matriks MK–CPL', 'href' => route('kurikulum.matriks.input')],
    2 => ['label' => 'CPMK',            'href' => $mk ? route('kurikulum.cpmk.show', $mk)      : route('kurikulum.cpmk.index')],
    3 => ['label' => 'Sub-CPMK',        'href' => $mk ? route('kurikulum.sub-cpmk.show', $mk) : route('kurikulum.sub-cpmk.index')],
    4 => ['label' => 'Bobot Asesmen',   'href' => $mk ? route('kurikulum.asesmen.show', $mk)   : route('kurikulum.asesmen.index')],
    5 => ['label' => 'Deskripsi MK',    'href' => $mk ? route('kurikulum.deskripsi.edit', $mk) : route('kurikulum.deskripsi.index')],
];

// Hitung status selesai dari data nyata (hanya jika MK spesifik diketahui)
$doneMap = null;
if ($mk) {
    $mk->loadCount([
        'cpmks',
        'subCpmks',
        'subCpmks as sub_asesmen_count' => fn($q) => $q->whereHas('asesmen'),
    ]);
    $hasMatriks = $mk->mkCpls()->whereNotNull('level')->exists();
    $doneMap = [
        1 => $hasMatriks,
        2 => $mk->cpmks_count > 0,
        3 => $mk->sub_cpmks_count > 0,
        4 => $mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count,
        5 => !empty($mk->deskripsi),
    ];
}

// Langkah terkunci: butuh langkah sebelumnya selesai
$lockedMap = [];
if ($doneMap) {
    $lockedMap = [
        1 => false,
        2 => false,
        3 => !$doneMap[2],   // Sub-CPMK butuh CPMK
        4 => !$doneMap[3],   // Bobot butuh Sub-CPMK
        5 => false,
    ];
}
@endphp

<div class="flex items-center gap-1 mb-6 overflow-x-auto pb-0.5">
    @foreach($steps as $no => $s)
    @php
        $active = $no == $step;
        $done   = $doneMap ? ($doneMap[$no] ?? false) : ($no < $step);
        $locked = $lockedMap[$no] ?? false;
    @endphp

    @if($locked && !$active)
    {{-- Langkah terkunci — tidak bisa diklik --}}
    <span title="Selesaikan langkah sebelumnya terlebih dahulu"
          class="flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-xl text-xs font-medium
                 text-gray-300 bg-gray-50 cursor-not-allowed select-none">
        <span class="w-4 h-4 rounded-full flex items-center justify-center shrink-0 bg-gray-100">
            <svg class="w-2.5 h-2.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </span>
        <span>{{ $s['label'] }}</span>
    </span>
    @else
    <a href="{{ $s['href'] }}"
       class="flex items-center gap-1.5 shrink-0 px-3 py-1.5 rounded-xl text-xs font-medium transition-all duration-150
              {{ $active
                    ? 'stepper-active bg-blue-600 text-white shadow-sm'
                    : ($done
                        ? 'text-green-700 bg-green-50 hover:bg-green-100'
                        : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600') }}">
        <span class="w-4 h-4 rounded-full flex items-center justify-center shrink-0
                     {{ $active ? 'bg-white/20' : ($done ? 'bg-green-100' : 'bg-gray-100') }}">
            @if($done)
                <svg class="w-2.5 h-2.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            @else
                <span class="text-[9px] font-bold {{ $active ? 'text-white' : 'text-gray-400' }}">{{ $no }}</span>
            @endif
        </span>
        <span>{{ $s['label'] }}</span>
    </a>
    @endif

    @if(!$loop->last)
    <svg class="w-3 h-3 shrink-0 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
    @endif
    @endforeach
</div>
@endif
