@extends('layouts.app')
@section('title','Bobot Asesmen – '.$mataKuliah->nama)
@section('content')

<x-page-header
    title="Bobot Asesmen — {{ $mataKuliah->nama }}"
    subtitle="{{ $mataKuliah->kode_rps }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}"
    back="{{ route('kurikulum.asesmen.index') }}"
    backLabel="Bobot Asesmen"/>

<div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-2.5 mb-5 text-xs text-blue-700 flex items-center gap-2">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    Isi bobot (%) untuk setiap metode asesmen. Total per Sub-CPMK harus = 100%.
    Perubahan disimpan otomatis.
</div>

@forelse($cpmks as $cpmk)
@if($cpmk->subCpmks->isNotEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
        <span class="font-semibold text-indigo-600 text-sm">{{ $cpmk->kode }}</span>
        <span class="text-sm text-gray-600">{{ Str::limit($cpmk->deskripsi, 80) }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left w-36">Sub-CPMK</th>
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left">Deskripsi</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Tugas</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Quis</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-24">Kasus/Proyek</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UTS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UAS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($cpmk->subCpmks as $sc)
                @php
                    $a = $sc->asesmen;
                    $total = $a ? $a->total : 0;
                @endphp
                <tr class="hover:bg-gray-50" id="row-{{ $sc->id }}">
                    <td class="px-4 py-2.5 font-medium text-teal-700 text-xs align-middle">{{ $sc->kode }}</td>
                    <td class="px-4 py-2.5 text-gray-600 text-xs align-middle">{{ $sc->deskripsi }}</td>

                    @foreach(['tugas','quis','kasus_proyek','uts','uas'] as $field)
                    <td class="px-3 py-2 text-center align-middle">
                        <input type="number" min="0" max="100" step="0.01"
                            id="{{ $field }}-{{ $sc->id }}"
                            value="{{ $a ? (float)$a->{$field} : '' }}"
                            placeholder="0"
                            oninput="debounce({{ $sc->id }})"
                            class="w-16 text-center border border-gray-200 rounded-lg px-1.5 py-1 text-xs
                                   focus:outline-none focus:ring-1 focus:ring-indigo-400 focus:border-indigo-400">
                    </td>
                    @endforeach

                    <td class="px-3 py-2 text-center align-middle">
                        <span id="total-{{ $sc->id }}"
                            class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $total == 100 ? 'bg-green-100 text-green-700' : ($total > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400') }}">
                            {{ $total > 0 ? $total.'%' : '–' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@empty
<div class="text-center py-16 text-gray-400">
    <p>Belum ada Sub-CPMK untuk mata kuliah ini.</p>
</div>
@endforelse

{{-- Status bar --}}
<div id="save-status" class="fixed bottom-4 right-4 text-xs px-3 py-1.5 rounded-full shadow hidden
    bg-white border border-gray-200 text-gray-600 transition-all"></div>

<script>
const CSRF  = '{{ csrf_token() }}';
const timers = {};

function debounce(id) {
    clearTimeout(timers[id]);
    showStatus('Mengetik...');
    timers[id] = setTimeout(() => saveRow(id), 500);
}

async function saveRow(id) {
    const fields = ['tugas','quis','kasus_proyek','uts','uas'];
    const body   = {};
    for (const f of fields) {
        body[f] = parseFloat(document.getElementById(f + '-' + id)?.value || 0) || 0;
    }

    showStatus('Menyimpan...');
    try {
        const res  = await fetch(`/kurikulum/api/asesmen/${id}`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify(body),
        });
        const json = await res.json();
        if (json.ok) {
            updateTotal(id, json.total);
            showStatus('✓ Tersimpan');
            setTimeout(() => hideStatus(), 2000);
        }
    } catch {
        showStatus('⚠ Gagal menyimpan');
    }
}

function updateTotal(id, total) {
    const el = document.getElementById('total-' + id);
    if (!el) return;
    el.textContent = total > 0 ? total + '%' : '–';
    el.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold ' +
        (total == 100 ? 'bg-green-100 text-green-700' :
         total  >  0  ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400');
}

function showStatus(msg) {
    const el = document.getElementById('save-status');
    el.textContent = msg;
    el.classList.remove('hidden');
}
function hideStatus() {
    document.getElementById('save-status')?.classList.add('hidden');
}
</script>

@endsection
