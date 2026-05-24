@extends('layouts.app')
@section('title','Mata Kuliah')
@section('content')

<x-page-header title="Mata Kuliah" subtitle="Daftar mata kuliah aktif dalam program studi">
    @hasanyrole('admin|kaprodi')
    <x-slot name="actions">
        <a href="{{ route('master.mata-kuliah.create') }}" class="btn-primary">+ Tambah MK</a>
    </x-slot>
    @endhasanyrole
</x-page-header>

<div class="flex justify-between items-center mb-6 gap-3 flex-wrap">
    <div class="flex items-center gap-2 flex-wrap">
        <form method="GET" class="flex items-center gap-2">
            <select name="program_studi_id" onchange="this.form.submit()"
                    class="form-input py-2 text-sm w-auto">
                @foreach($programStudis as $ps)
                <option value="{{ $ps->id }}" {{ $ps->id == $programStudiId ? 'selected' : '' }}>{{ $ps->nama }}</option>
                @endforeach
            </select>
            <select name="semester" onchange="this.form.submit()"
                    class="form-input py-2 text-sm w-auto">
                <option value="">Semua Semester</option>
                @for($s=1;$s<=8;$s++)
                <option value="{{ $s }}" {{ $semester == $s ? 'selected' : '' }}>Semester {{ $s }}</option>
                @endfor
            </select>
            <input type="hidden" name="program_studi_id" value="{{ $programStudiId }}">
        </form>
        <x-search-input placeholder="Cari kode atau nama MK..." />
    </div>
</div>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">
    Hasil untuk <strong class="text-gray-700">"{{ request('search') }}"</strong>
    — {{ $items->total() }} ditemukan
</p>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode RPS</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Mata Kuliah</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">SKS</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Smt</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Konsentrasi</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">CPMK</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Sub</th>
                @hasanyrole('admin|kaprodi')
                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-mono text-gray-800 text-xs">{{ $item->kode_rps }}</td>
                <td class="px-4 py-2.5 font-medium text-gray-800">
                    @hasanyrole('admin|kaprodi')
                    <a href="{{ route('master.mata-kuliah.show', $item) }}"
                       class="hover:text-gray-800 hover:underline">{{ $item->nama }}</a>
                    @else
                    {{ $item->nama }}
                    @endhasanyrole
                </td>
                <td class="px-4 py-2.5 text-center text-gray-600">{{ $item->sks }}</td>
                <td class="px-4 py-2.5 text-center text-gray-600">{{ $item->semester }}</td>
                <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $item->konsentrasi ?? '-' }}</td>
                <td class="px-4 py-2.5 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $item->cpmks_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $item->cpmks_count }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $item->sub_cpmks_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $item->sub_cpmks_count }}
                    </span>
                </td>
                @hasanyrole('admin|kaprodi')
                <td class="px-4 py-2.5 text-center space-x-2">
                    <a href="{{ route('master.mata-kuliah.show', $item) }}" class="text-gray-700 hover:underline text-xs font-medium">Detail</a>
                    <a href="{{ route('master.mata-kuliah.edit', $item) }}" class="text-gray-700 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('master.mata-kuliah.destroy', $item) }}" class="inline"
                          onsubmit="return confirm('Hapus mata kuliah ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
                @endhasanyrole
            </tr>
            @empty
            <tr><td colspan="{{ auth()->user()->hasRole('dosen') ? 7 : 8 }}" class="p-0">
                <x-empty-state icon="folder" title="Belum ada mata kuliah"
                    subtitle="Tambahkan mata kuliah baru untuk mulai mengisi data kurikulum."
                    action="{{ auth()->user()->hasRole('dosen') ? null : route('master.mata-kuliah.create') }}"
                    actionLabel="+ Tambah MK"/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $items->links() }}</div>
</div>
@endsection
