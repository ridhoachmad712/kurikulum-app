@extends('layouts.app')
@section('title','Log Aktivitas')
@section('content')

<x-page-header title="Log Aktivitas" subtitle="Riwayat audit perubahan data master — siapa mengubah apa & kapan"
               back="{{ route('super-admin.index') }}" backLabel="Super Admin">
    <x-slot name="actions">
        <form method="POST" action="{{ route('super-admin.logs.prune') }}"
              onsubmit="return confirm('Hapus semua log yang lebih lama dari 90 hari?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-secondary">Bersihkan Log Lama</button>
        </form>
    </x-slot>
</x-page-header>

@php
$actionMeta = [
    'created' => ['Tambah', 'bg-green-100 text-green-700', 'M12 4.5v15m7.5-7.5h-15'],
    'updated' => ['Ubah',   'bg-amber-100 text-amber-700', 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z'],
    'deleted' => ['Hapus',  'bg-red-100 text-red-700',     'M6 18L18 6M6 6l12 12'],
];
@endphp

{{-- Filter --}}
<div class="flex items-center gap-2 mb-5 flex-wrap">
    {{-- Aksi --}}
    <div class="flex items-center gap-1.5">
        <a href="{{ route('super-admin.logs.index', array_filter(['subject'=>$subject,'search'=>$search])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !$action ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua Aksi</a>
        @foreach($actionMeta as $key => [$label, $cls, $icon])
        <a href="{{ route('super-admin.logs.index', array_filter(['action'=>$key,'subject'=>$subject,'search'=>$search])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $action === $key ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $label }}</a>
        @endforeach
    </div>
    {{-- Subjek --}}
    <form method="GET" class="flex items-center gap-2">
        @if($action)<input type="hidden" name="action" value="{{ $action }}">@endif
        @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
        <select name="subject" onchange="this.form.submit()" class="form-input py-2 text-sm w-auto">
            <option value="">Semua Jenis Data</option>
            @foreach($subjects as $key => $label)
            <option value="{{ $key }}" {{ $subject === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
    <div class="ml-auto"><x-search-input placeholder="Cari data, pelaku, atau aksi..." /></div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">Aksi</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Deskripsi</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-44">Pelaku</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-44">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($logs as $log)
            @php [$aLabel, $aCls, $aIcon] = $actionMeta[$log->action] ?? [$log->action, 'bg-gray-100 text-gray-600', '']; @endphp
            <tr class="hover:bg-gray-50 align-top">
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $aCls }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $aIcon }}"/></svg>
                        {{ $aLabel }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <p class="text-gray-700">{{ $log->description }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">{{ $log->subject_label_human }}</span>
                        @if($log->action === 'updated' && !empty($log->changes))
                        <span class="text-[10px] text-gray-400">Kolom: {{ implode(', ', $log->changes) }}</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-gray-700 text-xs font-medium">{{ $log->user_name ?? '–' }}</p>
                    @if($log->ip_address)<p class="text-[10px] text-gray-400 font-mono">{{ $log->ip_address }}</p>@endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">
                    {{ $log->created_at?->format('d M Y, H:i') }}
                    <p class="text-[10px] text-gray-400">{{ $log->created_at?->diffForHumans() }}</p>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400 text-sm">Belum ada aktivitas tercatat.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
</div>

@endsection
