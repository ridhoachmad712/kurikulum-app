@extends('layouts.app')
@section('title','Penugasan Dosen ke Mata Kuliah')
@section('content')

<x-page-header title="Penugasan Dosen" subtitle="Tugaskan dosen ke mata kuliah yang menjadi tanggung jawabnya"/>

<div class="mb-5 flex items-center justify-between gap-3 flex-wrap">
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.penugasan.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('semester') ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Semua
        </a>
        @for($s=1;$s<=8;$s++)
        <a href="{{ route('admin.penugasan.index', ['semester'=>$s]) }}"
           class="px-3 py-2 text-sm font-medium rounded-lg {{ request('semester')==$s ? 'bg-gray-700 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
            Smt {{ $s }}
        </a>
        @endfor
    </div>
    <x-search-input placeholder="Cari kode atau nama MK..." />
</div>
@if(request('search'))
<p class="text-xs text-gray-500 mb-3">Hasil untuk <strong>"{{ request('search') }}"</strong></p>
@endif

<div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-2.5 mb-5 text-xs text-blue-700 flex items-center gap-2">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    Klik nama dosen untuk toggle penugasan. Dosen yang ditugaskan akan bertanggung jawab mengisi data MK tersebut.
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-4 py-3 text-left w-28">Kode</th>
                <th class="px-4 py-3 text-left">Nama Mata Kuliah</th>
                <th class="px-3 py-3 text-center w-12">Smt</th>
                <th class="px-3 py-3 text-center w-20">CPMK</th>
                <th class="px-3 py-3 text-center w-24">Sub-CPMK</th>
                <th class="px-4 py-3 text-left">Dosen Ditugaskan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($mataKuliahs as $mk)
            <tr class="hover:bg-gray-50" id="row-mk-{{ $mk->id }}">
                <td class="px-4 py-3 font-mono text-gray-700 text-xs">{{ $mk->kode_rps }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $mk->nama }}</td>
                <td class="px-3 py-3 text-center text-gray-500">{{ $mk->semester }}</td>
                <td class="px-3 py-3 text-center text-gray-500">{{ $mk->cpmks_count }}</td>
                <td class="px-3 py-3 text-center text-gray-500">{{ $mk->sub_cpmks_count }}</td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-1.5 items-center" id="dosens-{{ $mk->id }}">
                        @foreach($mk->dosens as $d)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                            {{ $d->name }}
                            <button onclick="toggleDosen({{ $mk->id }}, {{ $d->id }})"
                                class="ml-0.5 text-gray-400 hover:text-red-500 leading-none">&times;</button>
                        </span>
                        @endforeach
                        @if($mk->dosens->isEmpty())
                        <span id="no-dosen-{{ $mk->id }}" class="text-xs text-gray-300 italic">Belum ditugaskan</span>
                        @endif
                    </div>
                    {{-- Dropdown tambah dosen --}}
                    <div class="mt-1.5 flex items-center gap-2">
                        <select id="sel-{{ $mk->id }}"
                            class="border border-gray-200 rounded-lg text-xs px-2 py-1 focus:outline-none focus:ring-1 focus:ring-gray-400 max-w-xs">
                            <option value="">+ Tambah dosen...</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}{{ $d->nip ? ' ('.$d->nip.')' : '' }}</option>
                            @endforeach
                        </select>
                        <button onclick="addDosen({{ $mk->id }})"
                            class="px-2.5 py-1 bg-gray-700 text-white text-xs rounded-lg hover:bg-gray-700">
                            Tugaskan
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-0">
                <x-empty-state icon="folder" title="Belum ada mata kuliah"
                    subtitle="Tambahkan mata kuliah di menu Master terlebih dahulu sebelum melakukan penugasan dosen."/>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

async function toggleDosen(mkId, userId) {
    await doToggle(mkId, userId);
}
async function addDosen(mkId) {
    const sel = document.getElementById('sel-' + mkId);
    const userId = sel.value;
    if (!userId) return;
    await doToggle(mkId, userId);
    sel.value = '';
}

async function doToggle(mkId, userId) {
    const res  = await fetch(`/admin/penugasan/${mkId}/toggle`, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    JSON.stringify({ user_id: userId }),
    });
    const json = await res.json();
    if (!json.ok) return;
    renderDosens(mkId, json.dosens);
}

function renderDosens(mkId, dosens) {
    const container = document.getElementById('dosens-' + mkId);
    if (!container) return;
    // hapus semua chip + no-dosen label
    container.innerHTML = '';
    if (dosens.length === 0) {
        container.innerHTML = '<span class="text-xs text-gray-300 italic">Belum ditugaskan</span>';
        return;
    }
    dosens.forEach(d => {
        const span = document.createElement('span');
        span.className = 'inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium';
        span.innerHTML = `${d.name} <button onclick="toggleDosen(${mkId}, ${d.id})" class="ml-0.5 text-gray-400 hover:text-red-500 leading-none">&times;</button>`;
        container.appendChild(span);
    });
}
</script>
@endsection
