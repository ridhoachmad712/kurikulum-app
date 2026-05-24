@extends('layouts.app')
@section('title','Bahan Kajian')
@section('content')

<div class="flex justify-between items-center mb-6 gap-3 flex-wrap">
    <p class="text-xs text-gray-500">{{ $items->count() }} Bahan Kajian terdaftar</p>
    @hasanyrole('admin|kaprodi')
    <div class="flex items-center gap-2">
        <a href="{{ route('master.bahan-kajian.matriks') }}"
           class="px-4 py-2 bg-gray-50 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-100 border border-gray-200 transition">
            Edit Matriks CPL &amp; MK
        </a>
        <a href="{{ route('master.bahan-kajian.create') }}" class="btn-primary">+ Tambah BK</a>
    </div>
    @endhasanyrole
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">Kode</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Bahan Kajian</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden md:table-cell">Deskripsi</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">CPL</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">MK</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-16">No</th>
                @hasanyrole('admin|kaprodi')
                <th class="px-4 py-3 text-center font-semibold text-gray-600 w-28">Aksi</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-bold text-gray-800 font-mono">{{ $item->kode }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nama }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-xs hidden md:table-cell">
                    {{ Str::limit($item->deskripsi, 80) }}
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                        {{ $item->cpl_count }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                        {{ $item->mk_count }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-gray-400">{{ $item->urutan }}</td>
                @hasanyrole('admin|kaprodi')
                <td class="px-4 py-3 text-center space-x-2">
                    <a href="{{ route('master.bahan-kajian.edit', $item) }}" class="text-gray-700 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('master.bahan-kajian.destroy', $item) }}" class="inline"
                          onsubmit="return confirm('Hapus Bahan Kajian ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
                @endhasanyrole
            </tr>
            @empty
            <tr><td colspan="{{ auth()->user()->hasRole('dosen') ? 6 : 7 }}" class="p-0">
                <x-empty-state icon="document" title="Belum ada Bahan Kajian"
                    subtitle="Tambahkan Bahan Kajian terlebih dahulu." />
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
