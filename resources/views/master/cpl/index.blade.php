@extends('layouts.app')
@section('title','CPL (Capaian Pembelajaran Lulusan)')
@section('content')

<div class="flex justify-between items-center mb-6 gap-3 flex-wrap">
    <div class="flex items-center gap-2 flex-wrap">
        <form method="GET" class="flex items-center gap-2">
            <select name="program_studi_id" onchange="this.form.submit()"
                    class="form-input py-2 text-sm w-auto">
                @foreach($programStudis as $ps)
                <option value="{{ $ps->id }}" {{ $ps->id == $programStudiId ? 'selected' : '' }}>{{ $ps->nama }}</option>
                @endforeach
            </select>
        </form>
        <x-search-input placeholder="Cari kode atau deskripsi..." />
    </div>
    @hasanyrole('admin|kaprodi')
    <a href="{{ route('master.cpl.create') }}" class="btn-primary">+ Tambah CPL</a>
    @endhasanyrole
</div>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong> — {{ $items->total() }} ditemukan</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">Kode</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Deskripsi CPL</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-40">Keterangan</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-16">No</th>
                @hasanyrole('admin|kaprodi')
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">Aksi</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-bold text-gray-800">{{ $item->kode }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $item->deskripsi }}</td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $item->keterangan }}</td>
                <td class="px-4 py-3 text-center text-gray-500">{{ $item->urutan }}</td>
                @hasanyrole('admin|kaprodi')
                <td class="px-4 py-3 text-center space-x-2">
                    <a href="{{ route('master.cpl.edit', $item) }}" class="text-gray-700 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('master.cpl.destroy', $item) }}" class="inline"
                          onsubmit="return confirm('Hapus CPL ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
                @endhasanyrole
            </tr>
            @empty
            <tr><td colspan="{{ auth()->user()->hasRole('dosen') ? 4 : 5 }}" class="p-0">
                <x-empty-state icon="document" title="Belum ada CPL"
                    subtitle="Tambahkan Capaian Pembelajaran Lulusan (CPL) terlebih dahulu."/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $items->links() }}</div>
</div>
@endsection
