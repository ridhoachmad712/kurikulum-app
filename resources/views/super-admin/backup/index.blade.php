@extends('layouts.app')
@section('title','Backup Database')
@section('content')

<x-page-header title="Backup Database" subtitle="Export seluruh data aplikasi sebagai file .sql"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin"/>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Panel export --}}
    <div class="lg:col-span-1">
        <div class="bg-gradient-to-br from-blue-900 to-blue-800 text-white rounded-2xl p-6">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
            </div>
            <h3 class="font-bold text-base mb-1">Export Sekarang</h3>
            <p class="text-xs text-blue-100/80 leading-relaxed mb-5">
                Unduh salinan lengkap basis data <span class="font-mono font-semibold text-white">{{ $database }}</span>
                dalam format SQL. Simpan di tempat aman sebagai cadangan.
            </p>
            <a href="{{ route('super-admin.backup.download') }}"
               class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 bg-white text-blue-900 text-sm font-bold rounded-xl hover:bg-blue-50 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Unduh Backup (.sql)
            </a>
        </div>

        <div class="mt-4 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
            <p class="text-xs text-amber-700 leading-relaxed">
                <strong>Catatan:</strong> File backup berisi seluruh data termasuk akun pengguna.
                Simpan dengan aman dan jangan bagikan ke pihak yang tidak berwenang.
            </p>
        </div>
    </div>

    {{-- Ringkasan isi DB --}}
    <div class="lg:col-span-2">
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3.5">
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ $tableCount }}</p>
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1.5">Tabel Data</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3.5">
                <p class="text-2xl font-bold text-gray-800 leading-none">{{ number_format($totalRows) }}</p>
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1.5">Total Baris</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-600">Isi Database (per tabel)</p>
            </div>
            <div class="overflow-x-auto max-h-[420px] overflow-y-auto">
                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-gray-50">
                        @foreach($rowCounts as $table => $count)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-2 font-mono text-xs text-gray-600">{{ $table }}</td>
                            <td class="px-5 py-2 text-right text-xs font-semibold text-gray-700">{{ number_format($count) }} baris</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
