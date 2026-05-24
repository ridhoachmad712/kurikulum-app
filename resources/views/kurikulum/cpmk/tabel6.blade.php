@extends('layouts.app')
@section('title','Tabel 6 – Keterkaitan CPMK & CPL')
@section('content')

@include('kurikulum._kurikulum-tabs')

<p class="text-xs text-gray-500 mb-4">Tanda <strong>V</strong> = CPMK mendukung CPL tersebut.</p>

@foreach($mataKuliahs as $mk)
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-800 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-mono text-xs text-gray-400">{{ $mk->kode_rps }}</span>
        <span class="font-semibold text-sm">{{ $mk->nama }}</span>
        <span class="text-xs text-gray-400 ml-auto">{{ $mk->cpmks->count() }} CPMK</span>
    </div>
    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left min-w-[260px] text-gray-600">CPMK</th>
                    @foreach($cpls as $cpl)
                    <th class="px-2 py-2 text-center w-16 text-gray-600" title="{{ $cpl->deskripsi }}">{{ $cpl->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                {{-- Baris ringkasan MK --}}
                <tr class="bg-gray-50">
                    <td class="px-4 py-2 font-semibold text-gray-800 italic text-xs">{{ $mk->nama }} (semua CPMK)</td>
                    @foreach($cpls as $cpl)
                    @php
                        $mkHas = $mk->cpmks->contains(fn($c) =>
                            $c->cpmkCpls->where('cpl_id',$cpl->id)->where('has_relation',true)->isNotEmpty()
                        );
                    @endphp
                    <td class="px-2 py-2 text-center">
                        @if($mkHas)<span class="text-gray-700 font-bold">V</span>@else<span class="text-gray-200">—</span>@endif
                    </td>
                    @endforeach
                </tr>
                @foreach($mk->cpmks as $cpmk)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700">
                        <span class="font-semibold text-gray-700 mr-1">{{ $cpmk->kode }}</span>
                        {{ Str::limit($cpmk->deskripsi, 80) }}
                    </td>
                    @foreach($cpls as $cpl)
                    @php $has = $cpmk->cpmkCpls->where('cpl_id',$cpl->id)->where('has_relation',true)->isNotEmpty(); @endphp
                    <td class="px-2 py-2 text-center border-l border-gray-50">
                        @if($has)<span class="text-green-600 font-bold">V</span>@else<span class="text-gray-200">—</span>@endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@if($mataKuliahs->isEmpty())
<div class="text-center py-12 text-gray-400">Belum ada CPMK yang diisi.</div>
@endif
@endsection
