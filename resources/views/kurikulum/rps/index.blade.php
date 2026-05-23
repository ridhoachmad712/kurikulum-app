@extends('layouts.app')
@section('title','Daftar RPS')
@section('content')

{{-- ── HEADER ── --}}
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-lg font-bold text-gray-800">Rencana Pembelajaran Semester (RPS)</h1>
        <p class="text-xs text-gray-400 mt-0.5">Kelola status pengisian RPS per mata kuliah</p>
    </div>
    <a href="{{ route('kurikulum.rps.create') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Buat RPS Baru
    </a>
</div>

{{-- ── FILTER ── --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex flex-wrap items-center gap-3">
    <div class="flex-1 min-w-48">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari kode / nama mata kuliah…"
               class="form-input w-full text-sm py-2">
    </div>
    <select name="status" onchange="this.form.submit()"
            class="form-input py-2 text-sm w-auto">
        <option value="">Semua Status</option>
        <option value="Draft"  {{ $status == 'Draft'  ? 'selected' : '' }}>Draft</option>
        <option value="Review" {{ $status == 'Review' ? 'selected' : '' }}>Review</option>
        <option value="Final"  {{ $status == 'Final'  ? 'selected' : '' }}>Final</option>
    </select>
    <button type="submit"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-xl transition-colors">
        Cari
    </button>
    @if(request('search') || request('status'))
    <a href="{{ route('kurikulum.rps.index') }}"
       class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        Reset
    </a>
    @endif
</form>

{{-- ── TABLE ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left">Mata Kuliah</th>
                    <th class="px-4 py-3 text-left">Tahun Ajaran</th>
                    <th class="px-4 py-3 text-left">Semester</th>
                    @hasanyrole('admin|kaprodi')
                    <th class="px-4 py-3 text-left">Dosen</th>
                    @endhasanyrole
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Diperbarui</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $rps)
                @php
                    $statusColor = match($rps->status) {
                        'Final'  => 'bg-green-100 text-green-700',
                        'Review' => 'bg-amber-100 text-amber-700',
                        default  => 'bg-gray-100 text-gray-500',
                    };
                    $user = auth()->user();
                    $isOwner  = $user->id === $rps->dosen_id;
                    $isAdmin  = $user->hasAnyRole('admin|kaprodi');
                    $isDraft  = $rps->status === 'Draft';
                    $isReview = $rps->status === 'Review';
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <p class="font-mono text-xs text-indigo-600 font-semibold">{{ $rps->mataKuliah->kode_rps }}</p>
                        <p class="text-gray-800 font-medium">{{ $rps->mataKuliah->nama }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $rps->tahun_ajaran }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $rps->semester }}</td>
                    @hasanyrole('admin|kaprodi')
                    <td class="px-4 py-3">
                        <span class="text-gray-700 text-xs">{{ $rps->dosen->name ?? '—' }}</span>
                    </td>
                    @endhasanyrole
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                            {{ $rps->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-gray-400">
                        {{ $rps->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                            {{-- Detail --}}
                            <a href="{{ route('kurikulum.rps.show', $rps) }}"
                               class="inline-flex items-center px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs rounded-lg transition-colors">
                                Detail
                            </a>

                            {{-- Edit (Draft only) --}}
                            @if($isDraft && ($isOwner || $isAdmin))
                            <a href="{{ route('kurikulum.rps.edit', $rps) }}"
                               class="inline-flex items-center px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg transition-colors">
                                Edit
                            </a>
                            @endif

                            {{-- Submit (Draft, owner dosen) --}}
                            @if($isDraft && $isOwner && $user->isDosen())
                            <form method="POST" action="{{ route('kurikulum.rps.submit', $rps) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Ajukan RPS ini untuk direview?')"
                                        class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs rounded-lg transition-colors">
                                    Ajukan
                                </button>
                            </form>
                            @endif

                            {{-- Approve / Reject (Review, admin/kaprodi) --}}
                            @if($isReview && $isAdmin)
                            <form method="POST" action="{{ route('kurikulum.rps.approve', $rps) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Setujui dan finalisasi RPS ini?')"
                                        class="px-2.5 py-1 bg-green-50 hover:bg-green-100 text-green-700 text-xs rounded-lg transition-colors">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('kurikulum.rps.reject', $rps) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Kembalikan RPS ke status Draft?')"
                                        class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 text-xs rounded-lg transition-colors">
                                    Tolak
                                </button>
                            </form>
                            @endif

                            {{-- Delete (Draft only, owner or admin) --}}
                            @if($isDraft && ($isOwner || $isAdmin))
                            <form method="POST" action="{{ route('kurikulum.rps.destroy', $rps) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus RPS ini secara permanen?')"
                                        class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium">Belum ada RPS</p>
                        <p class="text-xs mt-1">Buat RPS baru dengan klik tombol di atas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
        {{ $items->links() }}
    </div>
    @endif
</div>

@endsection
