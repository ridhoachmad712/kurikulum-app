@extends('layouts.app')
@section('title','Tabel 8 – Rekapitulasi Sub-CPMK')
@section('content')

@include('kurikulum._kurikulum-tabs')

{{-- Warna per domain (fallback abu jika ada domain baru di luar 3 default) --}}
@php
$domainStyle = [
    'Afektif'    => ['header' => 'bg-pink-50 text-pink-700',   'cell' => 'bg-pink-50',  'badge' => 'bg-pink-100 text-pink-700'],
    'Kognitif'   => ['header' => 'bg-blue-50 text-blue-700',   'cell' => 'bg-blue-50',  'badge' => 'bg-blue-100 text-blue-700'],
    'Psikomotor' => ['header' => 'bg-green-50 text-green-700', 'cell' => 'bg-green-50', 'badge' => 'bg-green-100 text-green-700'],
];
$defaultStyle = ['header' => 'bg-gray-50 text-gray-600', 'cell' => 'bg-gray-50', 'badge' => 'bg-gray-100 text-gray-600'];
@endphp

@forelse($mataKuliahs as $mk)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
        <span class="font-mono text-xs text-gray-700 mr-2">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-gray-800">{{ $mk->nama }}</span>
        <span class="ml-2 text-xs text-gray-400">Smt {{ $mk->semester }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-xs border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase tracking-wide">
                    <th class="border border-gray-200 px-3 py-2 text-left w-32">CPMK</th>
                    <th class="border border-gray-200 px-3 py-2 text-left w-36">Sub-CPMK</th>
                    <th class="border border-gray-200 px-3 py-2 text-left">Deskripsi</th>
                    @foreach($domains as $domain)
                    @php $st = $domainStyle[$domain] ?? $defaultStyle; @endphp
                    <th class="border border-gray-200 px-3 py-2 text-center w-28 {{ $st['header'] }}">
                        {{ $domain }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($mk->cpmks as $cpmk)
                    @foreach($cpmk->subCpmks as $sc)
                    @php
                        /* Kelompokkan CLO terpilih berdasarkan domain → [domain => cloTaxonomi] */
                        $cloByDomain = $sc->subCpmkClos
                            ->filter(fn($scl) => $scl->cloTaxonomi !== null)
                            ->keyBy(fn($scl) => $scl->cloTaxonomi->domain);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        @if($loop->first)
                        <td class="border border-gray-200 px-3 py-2 font-semibold text-gray-700 align-top"
                            rowspan="{{ $cpmk->subCpmks->count() }}">
                            {{ $cpmk->kode }}
                        </td>
                        @endif
                        <td class="border border-gray-200 px-3 py-2 font-medium text-teal-700">{{ $sc->kode }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-gray-700">{{ $sc->deskripsi }}</td>

                        @foreach($domains as $domain)
                        @php
                            $st  = $domainStyle[$domain] ?? $defaultStyle;
                            $clo = $cloByDomain->get($domain)?->cloTaxonomi;
                        @endphp
                        <td class="border border-gray-200 px-3 py-2 text-center {{ $st['cell'] }}">
                            @if($clo)
                            <span class="inline-block px-2 py-0.5 rounded font-mono font-semibold {{ $st['badge'] }}"
                                  title="{{ $clo->deskripsi }} · L{{ $clo->level }}">
                                {{ $clo->kode }}
                            </span>
                            @else
                            <span class="text-gray-300">–</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="text-center py-16 text-gray-400">
    <p class="text-lg font-medium">Belum ada Sub-CPMK</p>
    <p class="text-sm mt-1">Isi Sub-CPMK terlebih dahulu.</p>
</div>
@endforelse

@endsection
