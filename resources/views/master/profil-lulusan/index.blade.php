@extends('layouts.app')
@section('title', 'Profil Lulusan')
@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-800">Profil Lulusan</h2>
        @if($prodi)
        <p class="text-xs text-gray-400 mt-0.5">{{ $prodi->nama }} &mdash; {{ $prodi->universitas }}</p>
        @endif
    </div>
    @hasanyrole('admin|kaprodi')
    <a href="{{ route('master.profil-lulusan.create') }}"
       class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Profil Lulusan
    </a>
    @endhasanyrole
</div>

@if($items->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400 text-sm">
    Belum ada data profil lulusan.
</div>
@else
<div class="space-y-4">
    @foreach($items as $pl)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center shrink-0">
                <span class="text-gray-800 font-bold text-sm">{{ $pl->kode }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm">{{ $pl->nama }}</p>
                @if($pl->deskripsi)
                <p class="text-gray-500 text-sm mt-1 leading-relaxed">{{ $pl->deskripsi }}</p>
                @endif
            </div>
            @hasanyrole('admin|kaprodi')
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('master.profil-lulusan.edit', $pl) }}"
                   class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('master.profil-lulusan.destroy', $pl) }}"
                      onsubmit="return confirm('Hapus {{ $pl->kode }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        Hapus
                    </button>
                </form>
            </div>
            @endhasanyrole
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
