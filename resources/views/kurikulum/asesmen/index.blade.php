@extends('layouts.app')
@section('title','Bobot Asesmen Sub-CPMK')
@section('content')

<x-page-header title="Bobot Asesmen" subtitle="Input bobot metode asesmen per Sub-CPMK (Tugas, Kuis, UTS, UAS, dll.)"/>

<x-dosen-stepper :step="4" />

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <x-search-input placeholder="Cari kode atau nama MK..." />
</div>
{{-- Info scope dosen --}}
@hasrole('dosen')
<div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Menampilkan hanya mata kuliah yang ditugaskan kepada Anda. Hubungi Kaprodi jika ada MK yang tidak muncul.</span>
</div>
@endhasrole

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
                    @if($mk->total_sub === 0)
                    {{-- Semua sub belum punya CLO — progress tidak bermakna --}}
                    <a href="{{ route('kurikulum.cpmk.show', $mk) }}"
                       class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium
                              bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 transition-colors">
                        ⚠ Belum ada CLO
                    </a>
                    @else
                    @php $pct = round($mk->filled_sub / $mk->total_sub * 100); @endphp
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $pct == 100 ? 'bg-green-500' : 'bg-amber-400' }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 w-14 text-right">{{ $mk->filled_sub }}/{{ $mk->total_sub }}</span>
                    </div>
                    @endif
                </td>
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('kurikulum.asesmen.show', $mk) }}"
                       class="btn-primary btn-sm">
                        Input
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-0">
                @if(auth()->user()->hasRole('dosen'))
                    <x-empty-state icon="document"
                        title="Belum ada mata kuliah yang ditugaskan"
                        subtitle="Anda belum mendapat penugasan mata kuliah. Hubungi Kaprodi untuk pengaturan penugasan."/>
                @else
                    <x-empty-state icon="document" title="Belum ada Sub-CPMK"
                        subtitle="Isi Sub-CPMK terlebih dahulu sebelum mengatur bobot asesmen."
                        action="{{ route('kurikulum.sub-cpmk.index') }}"
                        actionLabel="Isi Sub-CPMK"/>
                @endif
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
