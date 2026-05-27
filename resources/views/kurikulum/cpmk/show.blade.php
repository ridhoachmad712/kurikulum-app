@extends('layouts.app')
@section('title','CPMK – '.$mataKuliah->nama)
@section('content')

<x-page-header
    title="CPMK — {{ $mataKuliah->nama }}"
    subtitle="{{ $mataKuliah->kode_rps }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester ?? '–' }}"
    back="{{ route('kurikulum.cpmk.index') }}"
    backLabel="CPMK"/>

@if($cpls->isEmpty())
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700 mb-6">
    ⚠ Mata kuliah ini belum memiliki keterkaitan CPL. Silakan isi
    <a href="{{ route('kurikulum.matriks.input') }}" class="underline font-medium">Matriks MK–CPL</a> terlebih dahulu.
</div>
@endif

{{-- TAB --}}
<div x-data="{ tab: (location.hash === '#bobot') ? 'bobot' : 'cpmk' }">
<div class="flex gap-1 mb-6 border-b border-gray-200">
    <button @click="tab='cpmk'; location.hash='cpmk'"
            :class="tab==='cpmk' ? 'bg-white border border-b-white border-gray-200 text-gray-800 -mb-px' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition">
        Input CPMK & Keterkaitan CPL
    </button>
    <button @click="tab='bobot'; location.hash='bobot'"
            :class="tab==='bobot' ? 'bg-white border border-b-white border-gray-200 text-gray-800 -mb-px' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition">
        Bobot % CPMK per CPL
    </button>
</div>

{{-- ===================== TAB 1: CPMK ===================== --}}
<div x-show="tab==='cpmk'" x-cloak class="w-full">

{{-- Panduan pemilihan CPL --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-4 text-xs text-blue-700">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
    </svg>
    <div class="space-y-1">
        <p class="font-semibold text-blue-800">Panduan Pemilihan CPL</p>
        <p>CPL yang ditampilkan adalah CPL yang sudah dikaitkan dengan mata kuliah ini di menu <strong>Level CPL Mata Kuliah</strong>.</p>
        <p>Untuk setiap CPMK, <strong>centang CPL yang didukung</strong> oleh CPMK tersebut — yaitu CPL yang pencapaiannya berkontribusi melalui CPMK ini. Satu CPMK bisa mendukung lebih dari satu CPL.</p>
        <p>Setelah memilih CPL, lanjutkan ke tab <strong>Bobot % CPMK per CPL</strong> untuk mengisi persentase bobotnya.</p>
    </div>
</div>

<div id="cpmk-list" class="space-y-3 mb-4">
    @foreach($cpmks as $cpmk)
    @include('kurikulum.cpmk._cpmk-card', ['cpmk'=>$cpmk, 'cpls'=>$cpls, 'cpmkCplMap'=>$cpmkCplMap])
    @endforeach
    @if($cpmks->isEmpty())
    <p class="text-gray-400 text-sm text-center py-6" id="empty-msg">Belum ada CPMK. Tambahkan di bawah.</p>
    @endif
</div>

{{-- Tombol buka modal --}}
@if($cpls->isNotEmpty())
<div class="flex justify-end">
    <button onclick="openAddModal()"
        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium rounded-xl transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah CPMK
    </button>
</div>
@endif

</div><!-- /tab cpmk -->

{{-- ===================== TAB 2: BOBOT ===================== --}}
<div x-show="tab==='bobot'" x-cloak class="w-full">

@if($cpmks->isEmpty())
<p class="text-gray-400 text-sm py-6 text-center">Belum ada CPMK. Tambahkan di tab pertama dahulu.</p>
@elseif($cpls->isEmpty())
<p class="text-amber-600 text-sm py-6 text-center">Belum ada CPL terkait untuk MK ini.</p>
@elseif(array_sum($tabel5) == 0)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-sm text-amber-700 flex items-start gap-3">
    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
    <div>
        <p class="font-semibold mb-1">Matriks MK–CPL belum diisi</p>
        <p class="text-xs text-amber-600">Nilai bobot CPMK diturunkan dari Tabel 5 (% bobot CPL per MK), namun data Matriks MK–CPL untuk mata kuliah ini masih kosong sehingga tidak ada acuan bobot.</p>
        <a href="{{ route('kurikulum.matriks.input') }}"
           class="inline-block mt-2.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg transition-colors">
            Isi Matriks MK–CPL →
        </a>
    </div>
</div>
@else

<div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-4 text-xs text-blue-700">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
    </svg>
    <div class="space-y-1">
        <p class="font-semibold text-blue-800">Panduan Pengisian Bobot</p>
        <p>Isi persentase kontribusi setiap CPMK terhadap CPL yang didukungnya. Kolom yang tidak aktif (—) berarti CPMK tersebut tidak mendukung CPL itu — pilih CPL-nya terlebih dahulu di tab <strong>Input CPMK & Keterkaitan CPL</strong>.</p>
        <p><strong>Total per kolom (CPL) harus sama dengan nilai baris Acuan Tabel 5</strong> di bagian bawah tabel. Baris <em>Total Isian</em> akan berubah warna menjadi hijau jika sudah sesuai, kuning jika belum, dan merah jika melebihi.</p>
        <p>Setelah selesai mengisi, tekan tombol <strong>Simpan Bobot</strong> di bawah tabel. <strong>Isian tidak dapat disimpan jika total per kolom CPL belum sesuai dengan Acuan Tabel 5.</strong></p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-auto">
    <table class="text-xs w-full border-collapse">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="sticky left-0 bg-gray-800 px-4 py-2.5 text-left min-w-[220px]">CPMK</th>
                @foreach($cpls as $cpl)
                <th class="px-3 py-2.5 text-center w-24">{{ $cpl->kode }}</th>
                @endforeach
                <th class="px-3 py-2.5 text-center w-20 bg-gray-700">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpmks as $cpmk)
            <tr class="border-b border-gray-100 hover:bg-gray-50" id="bobot-row-{{ $cpmk->id }}">
                <td class="sticky left-0 bg-white px-4 py-2 border-r border-gray-100">
                    <span class="font-semibold text-gray-800">{{ $cpmk->kode }}</span>
                    <p class="text-gray-500 text-xs mt-0.5 line-clamp-1">{{ $cpmk->deskripsi }}</p>
                </td>
                @foreach($cpls as $cpl)
                @php
                    $hasRel = $cpmkCplMap[$cpmk->id][$cpl->id]['has_relation'] ?? false;
                    $bobot  = $cpmkCplMap[$cpmk->id][$cpl->id]['bobot'] ?? 0;
                @endphp
                <td id="bobot-cell-{{ $cpmk->id }}-{{ $cpl->id }}" class="px-2 py-2 text-center">
                    @if($hasRel)
                        <input type="number" min="0" max="100" step="1"
                            value="{{ $bobot > 0 ? $bobot : '' }}"
                            placeholder="0"
                            data-cpmk="{{ $cpmk->id }}"
                            data-cpl="{{ $cpl->id }}"
                            oninput="onBobotInput(this)"
                            class="bobot-input w-20 border border-gray-200 rounded px-2 py-1 text-center text-xs
                                   focus:ring-gray-400 focus:border-gray-400 focus:outline-none
                                   {{ $bobot > 0 ? 'border-indigo-300 bg-gray-50' : '' }}">
                    @else
                        <span class="text-gray-200 select-none">—</span>
                    @endif
                </td>
                @endforeach
                <td class="px-2 py-2 text-center font-bold text-gray-400" id="total-{{ $cpmk->id }}">
                    @php
                        $rowTotal = collect($cpmkCplMap[$cpmk->id] ?? [])
                            ->where('has_relation', true)
                            ->sum('bobot');
                    @endphp
                    <span class="text-gray-400">
                        {{ $rowTotal > 0 ? round($rowTotal).'%' : '—' }}
                    </span>
                </td>
            </tr>
            @endforeach

            {{-- Baris: Acuan Tabel 5 --}}
            @php
                $colTotals = [];
                foreach ($cpls as $cpl) {
                    $colTotals[$cpl->id] = collect($cpmkCplMap)
                        ->filter(fn($m) => ($m[$cpl->id]['has_relation'] ?? false))
                        ->sum(fn($m) => $m[$cpl->id]['bobot'] ?? 0);
                }
            @endphp
            <tr class="border-t-2 border-gray-200 bg-emerald-50">
                <td class="sticky left-0 bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-700 border-r border-gray-100">
                    Acuan Tabel 5
                </td>
                @foreach($cpls as $cpl)
                @php $ref = $tabel5[$cpl->id] ?? 0; @endphp
                <td class="px-2 py-2 text-center text-xs font-semibold text-emerald-700">
                    {{ $ref > 0 ? $ref.'%' : '—' }}
                </td>
                @endforeach
                <td class="px-2 py-2 text-center text-xs text-emerald-600 font-semibold">
                    {{ array_sum($tabel5) }}%
                </td>
            </tr>

            {{-- Baris: Total isian saat ini --}}
            <tr class="bg-gray-50 border-t border-gray-200">
                <td class="sticky left-0 bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-600 border-r border-gray-100">
                    Total Isian
                </td>
                @foreach($cpls as $cpl)
                @php
                    $col    = round($colTotals[$cpl->id] ?? 0);
                    $ref    = $tabel5[$cpl->id] ?? 0;
                    $match  = $ref > 0 && abs($col - $ref) < 0.2;
                    $over   = $ref > 0 && $col > $ref + 0.2;
                    $colCls = $match ? 'text-green-600 font-bold'
                            : ($over  ? 'text-red-600 font-bold'
                            : ($col > 0 ? 'text-amber-600 font-semibold' : 'text-gray-300'));
                @endphp
                <td id="col-total-{{ $cpl->id }}" class="px-2 py-2 text-center text-xs {{ $colCls }}">
                    {{ $col > 0 ? $col.'%' : '—' }}
                    @if($over)
                        <span class="block text-[9px] font-normal leading-tight">⚠ melebihi</span>
                    @endif
                </td>
                @endforeach
                <td class="px-2 py-2 text-center text-xs text-gray-400">
                    {{ round(array_sum($colTotals)) }}%
                </td>
            </tr>
        </tbody>
    </table>
</div>{{-- /overflow-auto --}}

{{-- Validasi error --}}
<div id="bobot-errors" class="hidden mt-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
    <p class="font-semibold text-red-800">Bobot belum sesuai acuan — perbaiki kolom berikut:</p>
    <ul id="bobot-error-list" class="list-disc list-inside space-y-0.5 mt-1"></ul>
</div>

{{-- Tombol Simpan --}}
<div class="mt-4 flex justify-end">
    <button id="btn-simpan-bobot" onclick="simpanSemuaBobot()"
        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg
               transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
        Simpan Bobot
    </button>
</div>
@endif

</div><!-- /tab bobot -->
</div><!-- /x-data tabs -->

{{-- ===== MODAL TAMBAH CPMK ===== --}}
@if($cpls->isNotEmpty())
<div id="modal-add-cpmk"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4"
     onclick="if(event.target===this) closeAddModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Tambah CPMK Baru</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $mataKuliah->kode_rps }} — {{ $mataKuliah->nama }}</p>
            </div>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 space-y-5">

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Deskripsi CPMK <span class="text-red-400">*</span>
                </label>
                <textarea id="modal-deskripsi" rows="3"
                    placeholder="Gunakan kata kerja operasional yang terukur (mis. Mampu menganalisis…)"
                    oninput="validateAddModal()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:ring-1 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none resize-none"></textarea>
            </div>

            {{-- Pilihan CPL --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                    CPL yang Didukung <span class="text-red-400">*</span>
                    <span class="ml-1 font-normal text-gray-400">(pilih minimal 1)</span>
                </label>
                <div class="flex flex-wrap gap-2" id="modal-cpl-list">
                    @foreach($cpls as $cpl)
                    <button type="button"
                        data-cpl-id="{{ $cpl->id }}"
                        data-selected="0"
                        onclick="toggleModalCpl(this)"
                        title="{{ $cpl->deskripsi ?? $cpl->kode }}"
                        class="modal-cpl-btn px-3 py-1.5 rounded-full border text-xs font-medium transition-all
                               bg-gray-50 border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-700">
                        {{ $cpl->kode }}
                    </button>
                    @endforeach
                </div>
                <p id="modal-cpl-err" class="hidden mt-2 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Pilih minimal satu CPL yang didukung.
                </p>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <p class="text-xs text-gray-400">Relasi CPL dapat diubah lagi setelah CPMK tersimpan.</p>
            <div class="flex gap-2 shrink-0">
                <button onclick="closeAddModal()"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 font-medium transition">Batal</button>
                <button id="btn-modal-add" onclick="submitAddCpmk()" disabled
                    class="px-5 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition
                           disabled:opacity-40 disabled:cursor-not-allowed">
                    Simpan CPMK
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Toast notifikasi --}}
<div id="cpmk-toast"
     class="fixed bottom-4 right-4 z-50 hidden px-4 py-2 rounded-full shadow-lg
            bg-green-600 text-white text-xs font-medium transition-all"></div>

<script>
const CSRF = '{{ csrf_token() }}';

// ── Flash after reload ────────────────────────────────────────────
const flashMsg = sessionStorage.getItem('cpmk_flash');
if (flashMsg) {
    sessionStorage.removeItem('cpmk_flash');
    const toast = document.getElementById('cpmk-toast');
    if (toast) {
        toast.textContent = '✓ ' + flashMsg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
}

// ── Modal Tambah CPMK ─────────────────────────────────────────────
function openAddModal() {
    document.getElementById('modal-deskripsi').value = '';
    document.querySelectorAll('.modal-cpl-btn').forEach(btn => {
        btn.dataset.selected = '0';
        btn.className = 'modal-cpl-btn px-3 py-1.5 rounded-full border text-xs font-medium transition-all ' +
            'bg-gray-50 border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-700';
    });
    document.getElementById('modal-cpl-err')?.classList.add('hidden');
    document.getElementById('btn-modal-add').disabled = true;
    const modal = document.getElementById('modal-add-cpmk');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('modal-deskripsi').focus(), 50);
}

function closeAddModal() {
    const modal = document.getElementById('modal-add-cpmk');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function toggleModalCpl(btn) {
    const nowSelected = btn.dataset.selected !== '1';
    btn.dataset.selected = nowSelected ? '1' : '0';
    if (nowSelected) {
        btn.className = 'modal-cpl-btn px-3 py-1.5 rounded-full border text-xs font-semibold transition-all ' +
            'bg-indigo-100 border-indigo-400 text-indigo-800 ring-2 ring-indigo-200';
    } else {
        btn.className = 'modal-cpl-btn px-3 py-1.5 rounded-full border text-xs font-medium transition-all ' +
            'bg-gray-50 border-gray-200 text-gray-500 hover:border-gray-400 hover:text-gray-700';
    }
    validateAddModal();
}

function validateAddModal() {
    const desc        = document.getElementById('modal-deskripsi')?.value.trim() ?? '';
    const cplSelected = document.querySelectorAll('.modal-cpl-btn[data-selected="1"]').length > 0;
    const valid       = desc.length > 0 && cplSelected;
    document.getElementById('btn-modal-add').disabled = !valid;
    // Tampilkan pesan error CPL hanya jika user sudah mulai mengisi deskripsi
    if (desc.length > 0 && !cplSelected) {
        document.getElementById('modal-cpl-err')?.classList.remove('hidden');
    } else {
        document.getElementById('modal-cpl-err')?.classList.add('hidden');
    }
    return valid;
}

async function submitAddCpmk() {
    if (!validateAddModal()) return;
    const desc        = document.getElementById('modal-deskripsi').value.trim();
    const selectedIds = [...document.querySelectorAll('.modal-cpl-btn[data-selected="1"]')]
                          .map(b => parseInt(b.dataset.cplId));
    const btn = document.getElementById('btn-modal-add');
    btn.disabled    = true;
    btn.textContent = 'Menyimpan…';
    try {
        // 1. Buat CPMK baru
        const res = await fetch('{{ route("kurikulum.cpmk.store", $mataKuliah) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ deskripsi: desc }),
        });
        if (!res.ok) throw new Error();
        const data   = await res.json();
        const cpmkId = data.cpmk.id;
        // 2. Set relasi CPL yang dipilih (parallel)
        await Promise.all(selectedIds.map(cplId =>
            fetch(`/kurikulum/api/cpmk/${cpmkId}/cpl-relation`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ cpl_id: cplId, has_relation: true }),
            })
        ));
        sessionStorage.setItem('cpmk_flash', `${data.cpmk.kode} berhasil ditambahkan.`);
        location.reload();
    } catch {
        alert('Gagal menyimpan. Silakan coba lagi.');
        btn.disabled    = false;
        btn.textContent = 'Simpan CPMK';
    }
}

// Tutup modal dengan Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAddModal();
});

// ── Delete CPMK ───────────────────────────────────────────────────
async function deleteCpmk(id, kode) {
    if (!confirm(`Hapus ${kode}?\n\nSemua data turunan berikut akan ikut terhapus permanen:\n• Sub-CPMK di bawah ${kode}\n• CLO yang dipilih per Sub-CPMK\n• Bobot asesmen per Sub-CPMK\n• Relasi CPL\n\nLanjutkan?`)) return;
    await fetch(`/kurikulum/api/cpmk/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    document.getElementById(`cpmk-card-${id}`)?.remove();
    if (!document.querySelector('.cpmk-card')) {
        const el = document.getElementById('empty-msg');
        if (el) el.classList.remove('hidden');
    }
}

// ── Toggle CPL relation ───────────────────────────────────────────
async function toggleCpl(btn, cpmkId, cplId) {
    const isChecked = btn.dataset.checked === '1';
    const newVal    = !isChecked;

    // Optimistic UI update (Tab 1)
    setCheckStyle(btn, newVal);

    try {
        const res = await fetch(`/kurikulum/api/cpmk/${cpmkId}/cpl-relation`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ cpl_id: cplId, has_relation: newVal }),
        });
        if (!res.ok) throw new Error();
        const json = await res.json();

        btn.dataset.checked = newVal ? '1' : '0';

        // Sync sel bobot di Tab 2
        syncBobotCell(cpmkId, cplId, newVal);

        // Update total row dari server
        updateBobotTotal(cpmkId, json.total_bobot ?? null);

    } catch {
        // Revert on failure
        setCheckStyle(btn, isChecked);
    }
}

function setCheckStyle(btn, checked) {
    if (checked) {
        btn.classList.remove('bg-gray-50', 'border-gray-200', 'text-gray-400', 'hover:border-gray-300');
        btn.classList.add('bg-indigo-100', 'border-indigo-400', 'text-indigo-800', 'font-semibold');
    } else {
        btn.classList.remove('bg-indigo-100', 'border-indigo-400', 'text-indigo-800', 'font-semibold');
        btn.classList.add('bg-gray-50', 'border-gray-200', 'text-gray-400', 'hover:border-gray-300');
    }
}

// Tampilkan / sembunyikan input bobot di Tab 2 sesuai relasi CPL
function syncBobotCell(cpmkId, cplId, hasRelation) {
    const cell = document.getElementById(`bobot-cell-${cpmkId}-${cplId}`);
    if (!cell) return;

    if (hasRelation) {
        cell.innerHTML =
            `<input type="number" min="0" max="100" step="1" value="" placeholder="0"
                data-cpmk="${cpmkId}" data-cpl="${cplId}" oninput="onBobotInput(this)"
                class="bobot-input w-20 border border-gray-200 rounded px-2 py-1 text-center text-xs
                       focus:ring-gray-400 focus:border-gray-400 focus:outline-none">`;
    } else {
        cell.innerHTML = `<span class="text-gray-200 select-none">—</span>`;
    }
}

// Perbarui kolom Total (row) sebuah CPMK — netral, tanpa acuan 100%
function updateBobotTotal(cpmkId, serverTotal) {
    const totalEl = document.getElementById(`total-${cpmkId}`);
    if (!totalEl) return;

    let t = serverTotal;
    if (t === null || t === undefined) {
        t = 0;
        document.querySelectorAll(`.bobot-input[data-cpmk="${cpmkId}"]`).forEach(inp => {
            t += parseFloat(inp.value) || 0;
        });
        t = Math.round(t * 10) / 10;
    }

    const span = totalEl.querySelector('span') ?? totalEl;
    span.className   = 'text-gray-400';
    span.textContent = t > 0 ? Math.round(t) + '%' : '—';

    // Perbarui juga kolom total (bottom rows) untuk setiap CPL yang terpengaruh
    refreshColTotals();
}

// Hitung ulang baris Total Isian (kolom per CPL) dari DOM
const TABEL5     = @json($tabel5 ?? []);
const CPL_CODES  = @json($cpls->pluck('kode', 'id')->toArray());

function refreshColTotals() {
    document.querySelectorAll('[id^="col-total-"]').forEach(cell => {
        const cplId = cell.id.replace('col-total-', '');
        let sum = 0;
        document.querySelectorAll(`.bobot-input[data-cpl="${cplId}"]`).forEach(inp => {
            sum += parseFloat(inp.value) || 0;
        });
        sum = Math.round(sum * 10) / 10;
        const ref   = parseFloat(TABEL5[cplId] ?? 0);
        const match = ref > 0 && Math.abs(sum - ref) < 0.2;
        const over  = ref > 0 && sum > ref + 0.2;

        cell.className = 'px-2 py-2 text-center text-xs ' +
            (match ? 'text-green-600 font-bold' :
             over  ? 'text-red-600 font-bold'   :
             sum > 0 ? 'text-amber-600 font-semibold' : 'text-gray-300');

        cell.innerHTML = (sum > 0 ? Math.round(sum) + '%' : '—') +
            (over ? '<span class="block text-[9px] font-normal leading-tight">⚠ melebihi</span>' : '');
    });
}

// ── Input bobot (live update totals, tanpa auto-save) ────────────
function onBobotInput(input) {
    const cpmkId = input.dataset.cpmk;
    const bobot  = parseFloat(input.value) || 0;

    input.classList.toggle('border-indigo-300', bobot > 0);
    input.classList.toggle('bg-gray-50', bobot > 0);

    // Perbarui kolom Total baris CPMK dari DOM
    let rowTotal = 0;
    document.querySelectorAll(`.bobot-input[data-cpmk="${cpmkId}"]`).forEach(inp => {
        rowTotal += parseFloat(inp.value) || 0;
    });
    rowTotal = Math.round(rowTotal * 10) / 10;
    const totalEl = document.getElementById(`total-${cpmkId}`);
    if (totalEl) {
        const span = totalEl.querySelector('span') ?? totalEl;
        span.className   = 'text-gray-400';
        span.textContent = rowTotal > 0 ? Math.round(rowTotal) + '%' : '—';
    }

    refreshColTotals();
}

// ── Simpan semua bobot (dengan validasi) ─────────────────────────
async function simpanSemuaBobot() {
    // Validasi: setiap kolom CPL dengan acuan > 0 harus sesuai
    const errors = [];
    for (const [cplId, ref] of Object.entries(TABEL5)) {
        if (ref <= 0) continue;
        let sum = 0;
        document.querySelectorAll(`.bobot-input[data-cpl="${cplId}"]`).forEach(inp => {
            sum += parseFloat(inp.value) || 0;
        });
        sum = Math.round(sum * 10) / 10;
        if (Math.abs(sum - ref) >= 0.2) {
            const kode = CPL_CODES[cplId] ?? `CPL-${cplId}`;
            errors.push(`${kode}: total isian ${Math.round(sum)}% — acuan ${Math.round(ref)}%`);
        }
    }

    const errBox  = document.getElementById('bobot-errors');
    const errList = document.getElementById('bobot-error-list');

    if (errors.length > 0) {
        errList.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        errBox.classList.remove('hidden');
        errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        return;
    }

    errBox.classList.add('hidden');

    const btn = document.getElementById('btn-simpan-bobot');
    btn.disabled    = true;
    btn.textContent = 'Menyimpan…';

    const inputs    = document.querySelectorAll('.bobot-input');
    const promises  = [];
    inputs.forEach(inp => {
        const cpmkId = inp.dataset.cpmk;
        const cplId  = inp.dataset.cpl;
        const bobot  = parseFloat(inp.value) || 0;
        promises.push(
            fetch(`/kurikulum/api/cpmk/${cpmkId}/bobot`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ cpl_id: cplId, bobot }),
            })
        );
    });

    try {
        await Promise.all(promises);
        const toast = document.getElementById('cpmk-toast');
        toast.textContent = '✓ Bobot berhasil disimpan';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    } catch {
        alert('Gagal menyimpan. Silakan coba lagi.');
    }

    btn.disabled    = false;
    btn.textContent = 'Simpan Bobot';
}
</script>
@endsection
