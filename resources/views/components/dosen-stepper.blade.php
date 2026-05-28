@props(['step', 'mataKuliah' => null])

@if(auth()->user()?->hasRole('dosen'))
@php
$steps = [
    1 => ['label' => 'Matriks MK–CPL', 'href' => route('kurikulum.matriks.input')],
    2 => ['label' => 'CPMK',            'href' => $mataKuliah ? route('kurikulum.cpmk.show', $mataKuliah)      : route('kurikulum.cpmk.index')],
    3 => ['label' => 'Sub-CPMK',        'href' => $mataKuliah ? route('kurikulum.sub-cpmk.show', $mataKuliah) : route('kurikulum.sub-cpmk.index')],
    4 => ['label' => 'Bobot Asesmen',   'href' => $mataKuliah ? route('kurikulum.asesmen.show', $mataKuliah)   : route('kurikulum.asesmen.index')],
];
@endphp

<div class="flex items-center gap-1 mb-6 overflow-x-auto pb-0.5">
    @foreach($steps as $no => $s)
    @php
        $done   = $no < $step;
        $active = $no == $step;
    @endphp

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

    @if(!$loop->last)
    <svg class="w-3 h-3 shrink-0 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
    </svg>
    @endif
    @endforeach
</div>
@endif
