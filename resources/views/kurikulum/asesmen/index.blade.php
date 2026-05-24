@extends('layouts.app')
@section('title','Bobot Asesmen Sub-CPMK')
@section('content')

<x-page-header title="Bobot Asesmen" subtitle="Input bobot metode asesmen per Sub-CPMK (Tugas, Quis, UTS, UAS, dll.)"/>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('kurikulum.asesmen.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('semester') ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Semua
        </a>
        @for($s=1;$s<=8;$s++)
        <a href="{{ route('kurikulum.asesmen.index', ['semester'=>$s]) }}"
           class="px-3 py-2 text-sm font-medium rounded-lg {{ request('semester')==$s ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Smt {{ $s }}
        </a>
        @endfor
    </div>
    <x-search-input placeholder="Cari kode atau nama MK..." />
</div>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong> — {{ $mataKuliahs->count() }} ditemukan</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode RPS</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-12">SKS</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-12">Smt</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-36">Progress Asesmen</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($mataKuliahs as $mk)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-mono text-gray-700 text-xs">{{ $mk->kode_rps }}</td>
                <td class="px-4 py-2.5 font-medium text-gray-800">{{ $mk->nama }}</td>
                <td class="px-4 py-2.5 text-center text-gray-500">{{ $mk->sks }}</td>
                <td class="px-4 py-2.5 text-center text-gray-500">{{ $mk->semester }}</td>
                <td class="px-4 py-2.5 text-center">
                    @php $pct = $mk->total_sub > 0 ? round($mk->filled_sub / $mk->total_sub * 100) : 0; @endphp
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $pct == 100 ? 'bg-green-500' : 'bg-amber-400' }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 w-14 text-right">{{ $mk->filled_sub }}/{{ $mk->total_sub }}</span>
                    </div>
                </td>
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('kurikulum.asesmen.show', $mk) }}"
                       class="text-xs px-3 py-1 bg-gray-700 text-white rounded-lg hover:bg-gray-700">
                        Input
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-0">
                <x-empty-state icon="document" title="Belum ada Sub-CPMK"
                    subtitle="Isi Sub-CPMK terlebih dahulu sebelum mengatur bobot asesmen."
                    action="{{ route('kurikulum.sub-cpmk.index') }}"
                    actionLabel="Isi Sub-CPMK"/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
