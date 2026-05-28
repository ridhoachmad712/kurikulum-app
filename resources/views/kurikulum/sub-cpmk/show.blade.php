@extends('layouts.app')
@section('title','Sub-CPMK: '.$mataKuliah->nama)
@section('content')

{{-- ── Header ── --}}
<div class="mb-5">
    <x-page-header
        title="Sub-CPMK — {{ $mataKuliah->kode_rps }}"
        subtitle="{{ $mataKuliah->nama }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}"
        back="{{ route('kurikulum.sub-cpmk.index') }}"
        backLabel="Sub-CPMK"/>
</div>

<x-dosen-stepper :step="3" :mataKuliah="$mataKuliah" />

{{-- ── Tab Navigation ── --}}
<div class="flex items-end gap-0 border-b border-gray-200 mb-5">

    <button id="tab-btn-subcpmk" onclick="switchTab('subcpmk')"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors
                   border-gray-800 text-gray-800">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Sub-CPMK
        <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full text-[11px] font-semibold leading-none">
            {{ $subCpmks->count() }}
        </span>
    </button>

    <button id="tab-btn-bobot" onclick="switchTab('bobot')"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors
                   border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 10h18M3 14h18M10 3v18M14 3v18"/>
            <rect x="3" y="3" width="18" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Distribusi Bobot
        @if($cpmks->isNotEmpty())
        <span class="bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded-full text-[11px] font-semibold leading-none">
            {{ $cpmks->count() }} CPMK
        </span>
        @endif
    </button>

</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TAB 1: Sub-CPMK — Tambah + Daftar kartu (CLO)             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="tab-subcpmk">

    {{-- Form tambah --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Tambah Sub-CPMK Baru</h3>
        <div class="flex gap-3">
            <textarea id="new-deskripsi" rows="2" placeholder="Tuliskan deskripsi Sub-CPMK..."
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 resize-none"></textarea>
            <button onclick="addSubCpmk()"
                class="shrink-0 px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-800 self-start">
                + Tambah
            </button>
        </div>
        <p id="add-msg" class="text-xs mt-1 hidden"></p>
    </div>

    {{-- Info: arahkan ke tab bobot --}}
    @if($subCpmks->isNotEmpty() && $cpmks->isNotEmpty())
    <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 mb-5 text-xs text-indigo-700">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>
            Setelah menambahkan Sub-CPMK, atur keterkaitan dan bobot ke setiap CPMK di tab
            <button onclick="switchTab('bobot')"
                    class="font-semibold underline underline-offset-2 hover:text-indigo-900">Distribusi Bobot →</button>
        </span>
    </div>
    @endif

    {{-- Daftar kartu --}}
    @if($subCpmks->isEmpty())
    <div id="empty-msg" class="text-center py-14 text-gray-400 text-sm">
        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M12 11v4m0 0h.01"/>
        </svg>
        Belum ada Sub-CPMK. Tambahkan di atas.
    </div>
    @endif

    <div id="sub-cpmk-list" class="space-y-3">
        @foreach($subCpmks as $sc)
            @include('kurikulum.sub-cpmk._sub-cpmk-card', ['sc' => $sc, 'clos' => $clos])
        @endforeach
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TAB 2: Distribusi Bobot — Tabel Matriks                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="tab-bobot" class="hidden">

@if($cpmks->isNotEmpty() && $subCpmks->isNotEmpty())
@php
    $grandTotal        = $cpmks->sum(fn($c) => $cpmkMeta[$c->id]['total_bobot'] ?? 0);
    $unconnectedScs    = $subCpmks->filter(fn($sc) => empty($cpmkConnections[$sc->id]));
    $unconnectedCpmks  = $cpmks->filter(fn($c) => !collect($cpmkConnections)->contains(fn($conns) => array_key_exists($c->id, $conns)));
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">

    <div class="px-5 pt-4 pb-3 bg-gray-50 border-b border-gray-100">
        <p class="text-sm font-semibold text-gray-800">Distribusi Bobot Sub-CPMK ke CPMK</p>
    </div>

    {{-- ── Warning: koneksi kosong ── --}}
    <div id="warning-unconnected"
         class="{{ ($unconnectedScs->isEmpty() && $unconnectedCpmks->isEmpty()) ? 'hidden' : '' }}
                flex items-start gap-3 bg-amber-50 border-b border-amber-200 px-5 py-3 text-xs text-amber-800">
        <svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="leading-relaxed">
            <p id="warning-sc" class="{{ $unconnectedScs->isEmpty() ? 'hidden' : '' }}">
                <span class="font-semibold">Sub-CPMK belum terhubung ke CPMK mana pun:</span>
                <span id="warning-sc-list">{{ $unconnectedScs->pluck('kode')->join(', ') }}</span>
            </p>
            <p id="warning-cpmk" class="{{ $unconnectedCpmks->isEmpty() ? 'hidden' : '' }}">
                <span class="font-semibold">CPMK belum memiliki Sub-CPMK:</span>
                <span id="warning-cpmk-list">{{ $unconnectedCpmks->pluck('kode')->join(', ') }}</span>
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse" id="bobot-matrix">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-3 py-2.5 text-left min-w-[200px] text-gray-600">CPMK</th>
                    @foreach($subCpmks as $sc)
                    <th class="border border-gray-200 px-1 py-2.5 text-center min-w-[72px]
                               {{ empty($cpmkConnections[$sc->id]) ? 'bg-amber-50 text-amber-700' : 'text-gray-600' }}"
                        data-sc="{{ $sc->id }}"
                        data-kode="{{ $sc->kode }}">
                        <span class="block font-semibold">{{ $sc->kode }}</span>
                    </th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2.5 text-center w-20 bg-amber-50 text-amber-700">Total</th>
                    <th class="border border-gray-200 px-2 py-2.5 text-center w-20 text-gray-500">
                        Acuan<br><span class="font-normal text-[10px]">(Tabel 7)</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($cpmks as $cpmk)
                @php
                    $acuan      = $cpmkMeta[$cpmk->id]['total_bobot'] ?? 0;
                    $autoBobot  = $cpmkMeta[$cpmk->id]['bobot_per_sub'] ?? 0;
                    $rowTotal   = 0;
                    foreach ($subCpmks as $sc) {
                        $scConns = $cpmkConnections[$sc->id] ?? [];
                        if (array_key_exists($cpmk->id, $scConns)) {
                            $b = $scConns[$cpmk->id];
                            $rowTotal += $b !== null ? (float)$b : $autoBobot;
                        }
                    }
                    $rowOk = $acuan > 0 && abs($rowTotal - $acuan) <= 0.2;
                @endphp
                <tr class="hover:bg-gray-50/60" id="matrix-row-{{ $cpmk->id }}">

                    {{-- Label CPMK --}}
                    <td class="border border-gray-200 px-3 py-2 align-middle min-w-[220px] max-w-xs">
                        <span class="font-semibold text-gray-700">{{ $cpmk->kode }}</span>
                        <span class="block text-[11px] text-gray-400 mt-0.5 leading-snug whitespace-normal">
                            {{ $cpmk->deskripsi }}
                        </span>
                    </td>

                    {{-- Sel per Sub-CPMK --}}
                    @foreach($subCpmks as $sc)
                    @php
                        $scConns   = $cpmkConnections[$sc->id] ?? [];
                        $connected = array_key_exists($cpmk->id, $scConns);
                        $bobot     = $connected ? $scConns[$cpmk->id] : null;
                    @endphp
                    <td class="border border-gray-200 p-1 text-center align-middle"
                        id="cell-{{ $cpmk->id }}-{{ $sc->id }}"
                        data-cpmk="{{ $cpmk->id }}"
                        data-sc="{{ $sc->id }}"
                        data-auto="{{ $autoBobot }}">

                        @if($connected)
                        {{-- Terkoneksi: input bobot --}}
                        <div class="flex flex-col items-center gap-0.5 py-0.5">
                            <input type="number" min="0" max="100" step="1"
                                id="inp-{{ $cpmk->id }}-{{ $sc->id }}"
                                data-cpmk="{{ $cpmk->id }}"
                                data-sc="{{ $sc->id }}"
                                data-auto="{{ $autoBobot }}"
                                data-is-auto="{{ $bobot === null ? '1' : '0' }}"
                                value="{{ $bobot !== null ? round((float)$bobot) : ($autoBobot > 0 ? round($autoBobot) : '') }}"
                                oninput="onBobotInput({{ $sc->id }}, {{ $cpmk->id }})"
                                class="w-14 text-center border rounded-lg px-1 py-1 text-xs transition-colors
                                       {{ $bobot !== null
                                           ? 'border-indigo-300 bg-indigo-50 text-indigo-700 font-bold'
                                           : 'border-gray-200 bg-gray-50 text-gray-500' }}
                                       focus:outline-none focus:ring-1 focus:ring-indigo-400 focus:border-indigo-300 focus:bg-white">
                            <button onclick="disconnectCell({{ $sc->id }}, {{ $cpmk->id }})"
                                    title="Lepas koneksi ini"
                                    class="text-[9px] text-gray-300 hover:text-red-400 transition-colors leading-none flex items-center gap-0.5 mt-0.5">
                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                lepas
                            </button>
                        </div>
                        @else
                        {{-- Belum terkoneksi: tombol hubungkan --}}
                        <button onclick="connectCell({{ $sc->id }}, {{ $cpmk->id }})"
                                title="Hubungkan {{ $sc->kode }} ke {{ $cpmk->kode }}"
                                class="w-full h-11 flex flex-col items-center justify-center rounded-lg
                                       border-2 border-dashed border-gray-200 text-gray-300
                                       hover:border-indigo-400 hover:text-indigo-500 hover:bg-indigo-50
                                       transition-all gap-0.5 group">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-[9px] opacity-0 group-hover:opacity-100 transition-opacity leading-none">Hubungkan</span>
                        </button>
                        @endif
                    </td>
                    @endforeach

                    {{-- Total baris --}}
                    <td class="border border-gray-200 px-2 py-1.5 text-center font-bold bg-amber-50"
                        id="row-total-{{ $cpmk->id }}"
                        data-acuan="{{ $acuan }}">
                        @if($rowTotal > 0)
                        <span class="block {{ $rowOk ? 'text-green-700' : ($rowTotal > $acuan + 0.2 ? 'text-red-600' : 'text-amber-700') }}">
                            {{ round($rowTotal) }}%
                            @if($rowOk) <span class="text-[10px]">✓</span> @endif
                        </span>
                        @if($acuan > 0)
                        <div class="h-1 bg-gray-200 rounded-full mt-1 mx-0.5">
                            <div class="h-1 rounded-full transition-all
                                        {{ $rowOk ? 'bg-green-500' : ($rowTotal > $acuan + 0.2 ? 'bg-red-400' : 'bg-amber-400') }}"
                                 style="width:{{ min(100, $acuan > 0 ? ($rowTotal / $acuan * 100) : 0) }}%"></div>
                        </div>
                        @endif
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>

                    {{-- Acuan --}}
                    <td class="border border-gray-200 px-2 py-1.5 text-center">
                        @if($acuan > 0)
                        <span class="font-semibold text-gray-600">{{ round($acuan) }}%</span>
                        <span class="block text-[9px] text-gray-400 font-normal">target</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach

                {{-- Baris total --}}
                <tr class="bg-gray-800 text-white font-bold text-xs">
                    <td class="border border-gray-700 px-3 py-2">Total</td>
                    @foreach($subCpmks as $sc)
                    @php
                        $colTotal = 0;
                        foreach ($cpmks as $cpmk) {
                            $scConns = $cpmkConnections[$sc->id] ?? [];
                            if (array_key_exists($cpmk->id, $scConns)) {
                                $b = $scConns[$cpmk->id];
                                $colTotal += $b !== null ? (float)$b : ($cpmkMeta[$cpmk->id]['bobot_per_sub'] ?? 0);
                            }
                        }
                    @endphp
                    <td class="border border-gray-700 px-1 py-2 text-center"
                        id="col-total-{{ $sc->id }}">
                        {{ $colTotal > 0 ? round($colTotal).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center bg-amber-900">
                        {{ round($grandTotal) }}%
                    </td>
                    <td class="border border-gray-700 px-2 py-2 text-center">
                        {{ round($grandTotal) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-x-4 gap-y-1 text-[10px] text-gray-400">
        <span>💡 <strong class="text-gray-500">Auto</strong> = total bobot CPMK ÷ jumlah Sub-CPMK yang terhubung · kosongkan input untuk pakai nilai ini</span>
        <span>· <strong class="text-green-600">Hijau</strong> = total baris sudah sesuai target · <strong class="text-amber-600">Kuning</strong> = masih kurang · <strong class="text-red-500">Merah</strong> = melebihi target</span>
    </div>

</div>
@else
{{-- Empty state saat belum ada data --}}
<div class="text-center py-16 text-gray-400 text-sm">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 10h18M3 14h18M10 3v18M14 3v18"/>
        <rect x="3" y="3" width="18" height="18" rx="2"/>
    </svg>
    @if($subCpmks->isEmpty())
    <p>Belum ada Sub-CPMK. Tambahkan terlebih dahulu.</p>
    @elseif($cpmks->isEmpty())
    <p>Belum ada CPMK yang terdaftar untuk mata kuliah ini.</p>
    @endif
    <button onclick="switchTab('subcpmk')"
            class="mt-3 inline-flex items-center gap-1.5 px-4 py-1.5 bg-gray-100 hover:bg-gray-200
                   text-gray-600 text-xs rounded-lg transition-colors">
        ← Kembali ke Sub-CPMK
    </button>
</div>
@endif

</div>{{-- /tab-bobot --}}

{{-- Toast --}}
<div id="toast-msg" class="fixed bottom-4 right-4 text-xs px-3 py-1.5 rounded-full shadow hidden
     bg-white border border-gray-200 text-gray-600 transition-all"></div>

<style>
/* Hilangkan spinner naik/turun pada input number di tabel bobot */
#bobot-matrix input[type=number]::-webkit-inner-spin-button,
#bobot-matrix input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
#bobot-matrix input[type=number] { -moz-appearance: textfield; appearance: textfield; }
</style>

<script>
const CSRF      = document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}';
const CPMK_META = @json($cpmkMeta);   // { cpmk_id: { total_bobot, sub_count, bobot_per_sub } }
const timers    = {};

/* ── Tambah Sub-CPMK ─────────────────────────────────────────────── */
async function addSubCpmk() {
    const desc = document.getElementById('new-deskripsi').value.trim();
    const msg  = document.getElementById('add-msg');
    if (!desc) { showMsg(msg, 'Deskripsi tidak boleh kosong.', 'text-red-500'); return; }

    const res  = await fetch('{{ route("kurikulum.sub-cpmk.store", $mataKuliah) }}', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:   JSON.stringify({ deskripsi: desc }),
    });
    const json = await res.json();
    if (!json.ok) { showMsg(msg, 'Gagal menyimpan.', 'text-red-500'); return; }

    document.getElementById('new-deskripsi').value = '';
    document.getElementById('empty-msg')?.remove();
    showMsg(msg, '✓ Sub-CPMK ditambahkan. Memuat ulang...', 'text-green-600');
    setTimeout(() => location.reload(), 700);
}

/* ── Edit / Hapus Sub-CPMK ───────────────────────────────────────── */
async function saveEdit(id) {
    const val = document.getElementById('desc-input-' + id).value.trim();
    if (!val) return;
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}`, {
        method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:   JSON.stringify({ deskripsi: val }),
    });
    if ((await res.json()).ok) {
        document.getElementById('desc-view-' + id).textContent = val;
        cancelEdit(id);
    }
}
function startEdit(id) {
    document.getElementById('desc-view-' + id).classList.add('hidden');
    document.getElementById('desc-edit-' + id).classList.remove('hidden');
    document.getElementById('desc-input-' + id).focus();
}
function cancelEdit(id) {
    document.getElementById('desc-view-' + id).classList.remove('hidden');
    document.getElementById('desc-edit-' + id).classList.add('hidden');
}
async function deleteSubCpmk(id, kode) {
    if (!confirm(`Hapus ${kode}?\nKoneksi CPMK, CLO, dan bobot asesmen akan ikut terhapus.\nLanjutkan?`)) return;
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF },
    });
    if ((await res.json()).ok) {
        document.getElementById('sc-card-' + id)?.remove();
        location.reload(); // reload agar tabel terbaru
    }
}

/* ── Tabel Matriks — Hubungkan sel ───────────────────────────────── */
async function connectCell(scId, cpmkId) {
    const res  = await fetch(`/kurikulum/api/sub-cpmk/${scId}/cpmk-relation`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:   JSON.stringify({ cpmk_id: cpmkId, has_relation: true }),
    });
    const json = await res.json();
    if (!json.ok) return;

    const cell     = document.getElementById(`cell-${cpmkId}-${scId}`);
    const autoBobt = parseFloat(cell?.dataset.auto) || 0;
    if (cell) {
        const autoVal  = autoBobt > 0 ? String(Math.round(autoBobt)) : '';
        const autoAttr = autoBobt > 0 ? 'data-is-auto="1"' : 'data-is-auto="0"';
        cell.innerHTML = `
            <div class="flex flex-col items-center gap-0.5 py-0.5">
                <input type="number" min="0" max="100" step="1"
                    id="inp-${cpmkId}-${scId}"
                    data-cpmk="${cpmkId}" data-sc="${scId}"
                    data-auto="${autoBobt}" ${autoAttr}
                    value="${autoVal}"
                    oninput="onBobotInput(${scId}, ${cpmkId})"
                    class="w-14 text-center border border-gray-200 bg-gray-50 rounded-lg px-1 py-1 text-xs text-gray-500
                           focus:outline-none focus:ring-1 focus:ring-indigo-400 focus:border-indigo-300 focus:bg-white">
                <button onclick="disconnectCell(${scId}, ${cpmkId})"
                        title="Lepas koneksi ini"
                        class="text-[9px] text-gray-300 hover:text-red-400 transition-colors leading-none flex items-center gap-0.5 mt-0.5">
                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    lepas
                </button>
            </div>`;
    }
    updateTableTotals();
    checkUnconnected();
    showToast('✓ Terhubung');
}

/* ── Tabel Matriks — Lepas koneksi ──────────────────────────────── */
async function disconnectCell(scId, cpmkId) {
    if (!confirm('Lepas koneksi ini?\nBobot yang sudah diisi akan dihapus.')) return;
    const res  = await fetch(`/kurikulum/api/sub-cpmk/${scId}/cpmk-relation`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:   JSON.stringify({ cpmk_id: cpmkId, has_relation: false }),
    });
    const json = await res.json();
    if (!json.ok) return;

    const cell = document.getElementById(`cell-${cpmkId}-${scId}`);
    if (cell) {
        cell.innerHTML = `
            <button onclick="connectCell(${scId}, ${cpmkId})"
                    title="Hubungkan"
                    class="w-full h-11 flex flex-col items-center justify-center rounded-lg
                           border-2 border-dashed border-gray-200 text-gray-300
                           hover:border-indigo-400 hover:text-indigo-500 hover:bg-indigo-50
                           transition-all gap-0.5 group">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </button>`;
    }
    updateTableTotals();
    checkUnconnected();
    showToast('✓ Koneksi dilepas');
}

/* ── Tabel Matriks — Simpan bobot ────────────────────────────────── */
function onBobotInput(scId, cpmkId) {
    // Tandai bahwa user sedang mengedit (bukan lagi nilai auto)
    const inp = document.getElementById(`inp-${cpmkId}-${scId}`);
    if (inp) inp.dataset.isAuto = '0';
    updateTableTotals();          // langsung update total saat mengetik
    debounceTableBobot(scId, cpmkId);
}

function debounceTableBobot(scId, cpmkId) {
    const key = `t-${scId}-${cpmkId}`;
    clearTimeout(timers[key]);
    timers[key] = setTimeout(() => saveTableBobot(scId, cpmkId), 600);
}

async function saveTableBobot(scId, cpmkId) {
    const inp    = document.getElementById(`inp-${cpmkId}-${scId}`);
    const bobot  = parseFloat(inp?.value) || 0;   // kosong / 0 → auto (null di backend)
    const autoVal = parseFloat(CPMK_META[cpmkId]?.bobot_per_sub) || 0;

    const res  = await fetch(`/kurikulum/api/sub-cpmk/${scId}/bobot`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:   JSON.stringify({ cpmk_id: cpmkId, bobot }),
    });
    const json = await res.json();
    if (!json.ok) return;

    if (inp) {
        const manual = bobot > 0;

        // Jika user hapus angka → kembalikan tampilan nilai auto (bilangan bulat)
        if (!manual && autoVal > 0) {
            inp.value = Math.round(autoVal);
            inp.dataset.isAuto = '1';
        }

        // Update style: abu-abu = auto, biru = manual
        inp.className = inp.className
            .replace(/border-\S+|bg-\S+|text-\S+|font-\S+/g, '')
            .trim();
        inp.classList.add(...(manual
            ? ['border-indigo-300', 'bg-indigo-50', 'text-indigo-700', 'font-bold', 'text-xs', 'text-center']
            : ['border-gray-200', 'bg-gray-50', 'text-gray-500', 'text-xs', 'text-center']));
    }

    updateTableTotals();
    showToast('✓ Tersimpan');
}

/* ── Hitung ulang total baris & kolom ───────────────────────────── */
function updateTableTotals() {
    const tbl = document.getElementById('bobot-matrix');
    if (!tbl) return;

    // Per baris (CPMK) — gunakan float presisi dari data-auto saat mode auto
    tbl.querySelectorAll('[id^="row-total-"]').forEach(cell => {
        const cpmkId = cell.id.replace('row-total-', '');
        let sum = 0;
        tbl.querySelectorAll(`input[data-cpmk="${cpmkId}"]`).forEach(inp => {
            sum += inp.dataset.isAuto === '1'
                ? (parseFloat(inp.dataset.auto) || 0)
                : (parseFloat(inp.value) || 0);
        });
        const acuan  = parseFloat(CPMK_META[cpmkId]?.total_bobot) || 0;
        const ok     = acuan > 0 && Math.abs(sum - acuan) <= 0.2;
        const over   = acuan > 0 && sum > acuan + 0.2;
        const pct    = acuan > 0 ? Math.min(100, (sum / acuan) * 100) : 0;
        const color  = ok ? 'text-green-700' : (over ? 'text-red-600' : 'text-amber-700');
        const barClr = ok ? 'bg-green-500' : (over ? 'bg-red-400' : 'bg-amber-400');
        const barBg  = 'bg-gray-200';

        cell.innerHTML = sum > 0
            ? `<span class="block ${color}">${Math.round(sum)}%${ok ? ' <span class="text-[10px]">✓</span>' : ''}</span>
               ${acuan > 0 ? `<div class="h-1 ${barBg} rounded-full mt-1 mx-0.5"><div class="h-1 ${barClr} rounded-full transition-all" style="width:${pct}%"></div></div>` : ''}`
            : '<span class="text-gray-300">—</span>';
    });

    // Per kolom (Sub-CPMK) — idem, gunakan float presisi saat auto
    tbl.querySelectorAll('[id^="col-total-"]').forEach(cell => {
        const scId = cell.id.replace('col-total-', '');
        let sum = 0;
        tbl.querySelectorAll(`input[data-sc="${scId}"]`).forEach(inp => {
            sum += inp.dataset.isAuto === '1'
                ? (parseFloat(inp.dataset.auto) || 0)
                : (parseFloat(inp.value) || 0);
        });
        cell.textContent = sum > 0 ? Math.round(sum) + '%' : '—';
    });
}

/* ── CLO ─────────────────────────────────────────────────────────── */
const CLO_DOMAIN_SEL = {
    'Kognitif':     ['bg-blue-600', 'border-blue-600'],
    'Afektif':      ['bg-green-600', 'border-green-600'],
    'Psikomotorik': ['bg-teal-600', 'border-teal-600'],
};

async function saveClo(scId, cloId) {
    const btn = document.getElementById(`clo-btn-${scId}-${cloId}`);
    if (btn) { btn.disabled = true; btn.style.opacity = '0.6'; }

    let json;
    try {
        const res = await fetch(`/kurikulum/api/sub-cpmk/${scId}/clo`, {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:   JSON.stringify({ clo_id: cloId }),
        });
        json = await res.json();
    } catch { showCloStatus(scId, false); if (btn) { btn.disabled=false; btn.style.opacity=''; } return; }

    if (btn) { btn.disabled=false; btn.style.opacity=''; }
    if (!json.ok) { showCloStatus(scId, false); return; }

    const badge = document.getElementById('clo-badge-' + scId);
    if (badge) badge.textContent = json.kode_clo_badge ?? '–';

    const wrap = document.getElementById('clo-badge-wrap-' + scId);
    if (wrap) {
        wrap.classList.toggle('bg-gray-100',   !json.kode_clo_badge);
        wrap.classList.toggle('text-gray-400', !json.kode_clo_badge);
        wrap.classList.toggle('bg-purple-100', !!json.kode_clo_badge);
        wrap.classList.toggle('text-purple-700', !!json.kode_clo_badge);
    }

    const selectedIds = json.selected_ids || [];
    document.querySelectorAll(`[data-sc-id="${scId}"]`).forEach(b => {
        const sel    = selectedIds.includes(parseInt(b.dataset.cloId));
        const domain = b.dataset.domain || '';
        const selCls = CLO_DOMAIN_SEL[domain] ?? ['bg-purple-600', 'border-purple-600'];

        // Hapus semua kemungkinan warna selected
        b.classList.remove(
            'bg-blue-600', 'border-blue-600',
            'bg-green-600', 'border-green-600',
            'bg-teal-600', 'border-teal-600',
            'bg-purple-600', 'border-purple-600',
            'text-white', 'bg-white', 'text-gray-500', 'border-gray-300'
        );

        if (sel) {
            b.classList.add(...selCls, 'text-white');
        } else {
            b.classList.add('bg-white', 'text-gray-500', 'border-gray-300');
        }
    });
    showCloStatus(scId, true);
}

function showCloStatus(scId, ok) {
    const el = document.getElementById((ok ? 'clo-save-status-' : 'clo-save-error-') + scId);
    const other = document.getElementById((ok ? 'clo-save-error-' : 'clo-save-status-') + scId);
    other?.classList.add('hidden');
    el?.classList.remove('hidden');
    setTimeout(() => el?.classList.add('hidden'), 2500);
}

/* ── Cek koneksi kosong & update warning ─────────────────────────── */
function checkUnconnected() {
    const tbl = document.getElementById('bobot-matrix');
    if (!tbl) return;

    // Cek kolom (Sub-CPMK tanpa koneksi CPMK mana pun)
    const emptyScs = [];
    tbl.querySelectorAll('thead th[data-sc]').forEach(th => {
        const scId    = th.dataset.sc;
        const hasConn = tbl.querySelectorAll(`input[data-sc="${scId}"]`).length > 0;
        th.classList.toggle('bg-amber-50',   !hasConn);
        th.classList.toggle('text-amber-700', !hasConn);
        th.classList.toggle('text-gray-600',   hasConn);
        if (!hasConn) emptyScs.push(th.dataset.kode);
    });

    // Cek baris (CPMK tanpa koneksi Sub-CPMK mana pun)
    const emptyCpmks = [];
    tbl.querySelectorAll('tbody tr[id^="matrix-row-"]').forEach(row => {
        const cpmkId  = row.id.replace('matrix-row-', '');
        const hasConn = row.querySelectorAll(`input[data-cpmk="${cpmkId}"]`).length > 0;
        const label   = row.querySelector('td span.font-semibold');
        if (!hasConn && label) emptyCpmks.push(label.textContent.trim());
    });

    // Update banner
    const banner    = document.getElementById('warning-unconnected');
    const scRow     = document.getElementById('warning-sc');
    const scList    = document.getElementById('warning-sc-list');
    const cpmkRow   = document.getElementById('warning-cpmk');
    const cpmkList  = document.getElementById('warning-cpmk-list');

    if (scRow)   scRow.classList.toggle('hidden', emptyScs.length === 0);
    if (scList)  scList.textContent = emptyScs.join(', ');
    if (cpmkRow)  cpmkRow.classList.toggle('hidden', emptyCpmks.length === 0);
    if (cpmkList) cpmkList.textContent = emptyCpmks.join(', ');
    if (banner)   banner.classList.toggle('hidden', emptyScs.length === 0 && emptyCpmks.length === 0);
}

/* ── Tab switching ───────────────────────────────────────────────── */
const TABS = ['subcpmk', 'bobot'];

function switchTab(name) {
    TABS.forEach(t => {
        const panel = document.getElementById('tab-' + t);
        const btn   = document.getElementById('tab-btn-' + t);
        const active = t === name;

        if (panel) panel.classList.toggle('hidden', !active);

        if (btn) {
            // Active state
            btn.classList.toggle('border-gray-800', active);
            btn.classList.toggle('text-gray-800',   active);
            // Inactive state
            btn.classList.toggle('border-transparent',       !active);
            btn.classList.toggle('text-gray-400',            !active);
            btn.classList.toggle('hover:text-gray-600',      !active);
            btn.classList.toggle('hover:border-gray-300',    !active);
        }
    });
    // Simpan posisi tab di URL hash (tahan saat reload)
    history.replaceState(null, '', location.pathname + location.search + '#' + name);
}

// Restore tab dari hash + cek koneksi kosong saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const hash = location.hash.replace('#', '');
    switchTab(TABS.includes(hash) ? hash : 'subcpmk');
    checkUnconnected();
});

/* ── Utilities ───────────────────────────────────────────────────── */
function showToast(msg) {
    const el = document.getElementById('toast-msg');
    if (!el) return;
    el.textContent = msg;
    el.classList.remove('hidden');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => el.classList.add('hidden'), 2000);
}
function showMsg(el, text, cls) {
    el.textContent = text;
    el.className   = 'text-xs mt-1 ' + cls;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}
</script>

@hasrole('dosen')
<div class="mt-6 flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
    <a href="{{ route('kurikulum.sub-cpmk.index') }}" class="btn-secondary text-sm">
        ← Kembali ke daftar MK
    </a>
    <a href="{{ route('kurikulum.asesmen.show', $mataKuliah) }}" class="btn-primary">
        Lanjut: Bobot Asesmen
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
@endhasrole

@endsection
