@extends('layouts.app')
@section('title','Program Studi')
@section('content')

<x-page-header title="Program Studi" subtitle="Kelola data program studi">
    <x-slot name="actions">
        <a href="{{ route('master.program-studi.create') }}" class="btn-primary">
            + Tambah Program Studi
        </a>
    </x-slot>
</x-page-header>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenjang</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Fakultas</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">CPL</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Mk</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-gray-800">{{ $item->kode }}</td>
                <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                <td class="px-4 py-3">{{ $item->jenjang }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $item->fakultas }}</td>
                <td class="px-4 py-3 text-center">{{ $item->cpls_count }}</td>
                <td class="px-4 py-3 text-center">{{ $item->mata_kuliahs_count }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $item->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                    <a href="{{ route('master.program-studi.edit', $item) }}" class="text-gray-700 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('master.program-studi.destroy', $item) }}" class="inline"
                          onsubmit="return confirm('Hapus program studi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $items->links() }}</div>
</div>
@endsection
