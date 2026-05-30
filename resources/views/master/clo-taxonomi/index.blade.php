@extends('layouts.app')
@section('title','CLO Taxonomy (Taksonomi Bloom)')
@section('content')

<x-page-header title="CLO Taxonomy" subtitle="Taksonomi Bloom sebagai dasar pengukuran capaian pembelajaran"/>

@if($items->isEmpty())
<x-empty-state icon="book" title="Belum ada data CLO Taxonomy"
    subtitle="Tambahkan entri taksonomi Bloom sebagai dasar pengukuran capaian pembelajaran."/>
@endif
@foreach($items as $domain => $clos)
<div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
        <h3 class="font-semibold text-gray-700">Domain: {{ $domain }}</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-semibold text-gray-600 w-16">Kode</th>
                <th class="px-4 py-2 text-center font-semibold text-gray-600 w-16">Level</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-600">Deskripsi</th>
                @hasanyrole('admin|kaprodi')
                <th class="px-4 py-2 text-center font-semibold text-gray-600 w-24">Aksi</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($clos as $clo)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2.5 font-bold text-gray-800">{{ $clo->kode }}</td>
                <td class="px-4 py-2.5 text-center text-gray-500">{{ $clo->level }}</td>
                <td class="px-4 py-2.5 text-gray-700">{{ $clo->deskripsi }}</td>
                @hasanyrole('admin|kaprodi')
                <td class="px-4 py-2.5 text-center">
                    <a href="{{ route('master.clo-taxonomi.edit', $clo) }}" class="text-gray-700 hover:underline text-xs">Edit</a>
                </td>
                @endhasanyrole
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach

@endsection
