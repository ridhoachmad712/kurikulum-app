@extends('layouts.app')
@section('title','Matriks Bahan Kajian')
@section('content')

<div class="mb-4 flex items-center justify-between gap-3 flex-wrap">
    <a href="{{ route('master.bahan-kajian.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke daftar</a>
    <p class="text-xs text-gray-500">
        {{ $bks->count() }} BK &middot; {{ $cpls->count() }} CPL &middot; {{ $mks->count() }} MK
    </p>
</div>

<form method="POST" action="{{ route('master.bahan-kajian.simpan-matriks') }}" x-data="{ tab: 'cpl' }">
    @csrf

    {{-- Tab switch --}}
    <div class="flex gap-1 mb-4 bg-gray-100 p-1 rounded-xl w-fit">
        <button type="button"
                @click="tab = 'cpl'"
                :class="tab === 'cpl' ? 'bg-white shadow text-indigo-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-lg text-sm transition-all">
            CPL × Bahan Kajian
        </button>
        <button type="button"
                @click="tab = 'mk'"
                :class="tab === 'mk' ? 'bg-white shadow text-indigo-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-lg text-sm transition-all">
            Mata Kuliah × Bahan Kajian
        </button>
    </div>

    {{-- ── Tab CPL × BK ── --}}
    <div x-show="tab === 'cpl'" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-indigo-800">Matriks CPL × Bahan Kajian</p>
                <span class="text-xs text-indigo-500">Centang bila CPL berkaitan dengan Bahan Kajian</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[200px] text-gray-600 bg-gray-50">CPL</th>
                            @foreach($bks as $bk)
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-gray-50">
                                <span class="font-semibold text-indigo-600">{{ $bk->kode }}</span>
                                <p class="text-[10px] text-gray-400 font-normal mt-0.5 leading-tight hidden sm:block">{{ Str::limit($bk->nama, 15) }}</p>
                            </th>
                            @endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-14 bg-amber-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpls as $cpl)
                        @php $cplBkCount = 0; @endphp
                        <tr class="hover:bg-indigo-50/30">
                            <td class="border border-gray-200 px-3 py-2">
                                <span class="font-semibold text-indigo-600 mr-1">{{ $cpl->kode }}</span>
                                <span class="text-gray-600">{{ Str::limit($cpl->deskripsi, 60) }}</span>
                            </td>
                            @foreach($bks as $bk)
                            @php
                                $key      = "{$cpl->id}_{$bk->id}";
                                $checked  = isset($cplBkSet[$key]);
                                if ($checked) $cplBkCount++;
                            @endphp
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                <input type="checkbox"
                                       name="cpl_bk[]"
                                       value="{{ $cpl->id }}_{{ $bk->id }}"
                                       {{ $checked ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-indigo-600 cursor-pointer">
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center bg-amber-50 font-bold text-amber-700">
                                {{ $cplBkCount }}
                            </td>
                        </tr>
                        @endforeach

                        {{-- Total per BK --}}
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td class="border border-indigo-800 px-3 py-2 text-xs">Total</td>
                            @foreach($bks as $bk)
                            @php
                                $col = $cpls->filter(fn($c) => isset($cplBkSet["{$c->id}_{$bk->id}"]))->count();
                            @endphp
                            <td class="border border-indigo-800 px-2 py-2 text-center text-xs">{{ $col }}</td>
                            @endforeach
                            <td class="border border-indigo-800 px-2 py-2 text-center bg-amber-900 text-xs">
                                {{ collect($cplBkSet)->count() }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Tab MK × BK ── --}}
    <div x-show="tab === 'mk'" x-cloak>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-amber-800">Matriks Mata Kuliah × Bahan Kajian</p>
                <span class="text-xs text-amber-600">Centang bila MK mempelajari Bahan Kajian tersebut</span>
            </div>
            <div class="overflow-x-auto">
                <table class="text-xs w-full border-collapse">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left min-w-[220px] text-gray-600 bg-gray-50">Mata Kuliah</th>
                            @foreach($bks as $bk)
                            <th class="border border-gray-200 px-2 py-2 text-center w-16 bg-gray-50">
                                <span class="font-semibold text-amber-600">{{ $bk->kode }}</span>
                                <p class="text-[10px] text-gray-400 font-normal mt-0.5 leading-tight hidden sm:block">{{ Str::limit($bk->nama, 15) }}</p>
                            </th>
                            @endforeach
                            <th class="border border-gray-200 px-2 py-2 text-center w-14 bg-indigo-50 text-gray-600">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mkBySemester as $smt => $smtMks)
                        {{-- Header semester --}}
                        <tr>
                            <td colspan="{{ $bks->count() + 2 }}"
                                class="border border-gray-200 px-3 py-1.5 bg-indigo-900 text-white font-semibold text-xs">
                                Semester {{ $smt }}
                            </td>
                        </tr>
                        @foreach($smtMks as $mk)
                        @php $mkBkCount = 0; @endphp
                        <tr class="hover:bg-amber-50/30">
                            <td class="border border-gray-200 px-3 py-2">
                                <span class="font-mono text-indigo-600 mr-1 text-[11px]">{{ $mk->kode_rps }}</span>
                                <span class="text-gray-700">{{ Str::limit($mk->nama, 45) }}</span>
                            </td>
                            @foreach($bks as $bk)
                            @php
                                $key     = "{$mk->id}_{$bk->id}";
                                $checked = isset($mkBkSet[$key]);
                                if ($checked) $mkBkCount++;
                            @endphp
                            <td class="border border-gray-200 px-2 py-2 text-center">
                                <input type="checkbox"
                                       name="mk_bk[]"
                                       value="{{ $mk->id }}_{{ $bk->id }}"
                                       {{ $checked ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-amber-500 cursor-pointer">
                            </td>
                            @endforeach
                            <td class="border border-gray-200 px-2 py-2 text-center bg-indigo-50 font-bold text-indigo-700">
                                {{ $mkBkCount }}
                            </td>
                        </tr>
                        @endforeach
                        @endforeach

                        {{-- Total per BK --}}
                        <tr class="bg-indigo-900 text-white font-bold">
                            <td class="border border-indigo-800 px-3 py-2 text-xs">Total</td>
                            @foreach($bks as $bk)
                            @php
                                $col = $mks->filter(fn($m) => isset($mkBkSet["{$m->id}_{$bk->id}"]))->count();
                            @endphp
                            <td class="border border-indigo-800 px-2 py-2 text-center text-xs">{{ $col }}</td>
                            @endforeach
                            <td class="border border-indigo-800 px-2 py-2 text-center bg-amber-900 text-xs">
                                {{ collect($mkBkSet)->count() }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tombol Simpan --}}
    <div class="mt-5 flex items-center gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 shadow-sm transition">
            Simpan Semua Perubahan
        </button>
        <a href="{{ route('master.bahan-kajian.index') }}"
           class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
        <p class="text-xs text-gray-400 ml-2">Perubahan di kedua tab akan disimpan sekaligus.</p>
    </div>
</form>

@endsection
