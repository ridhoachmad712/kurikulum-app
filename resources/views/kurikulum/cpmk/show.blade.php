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

<div id="cpmk-list" class="space-y-3 mb-4">
    @foreach($cpmks as $cpmk)
    @include('kurikulum.cpmk._cpmk-card', ['cpmk'=>$cpmk, 'cpls'=>$cpls, 'cpmkCplMap'=>$cpmkCplMap])
    @endforeach
    @if($cpmks->isEmpty())
    <p class="text-gray-400 text-sm text-center py-6" id="empty-msg">Belum ada CPMK. Tambahkan di bawah.</p>
    @endif
</div>

{{-- Form tambah CPMK --}}
@if($cpls->isNotEmpty())
<div class="bg-white border border-dashed border-indigo-300 rounded-xl p-4">
    <p class="text-xs font-semibold text-indigo-500 mb-2 uppercase tracking-wide">Tambah CPMK Baru</p>
    <form id="form-add-cpmk">
        @csrf
        <textarea id="new-deskripsi" rows="2" placeholder="Deskripsi CPMK baru (gunakan kata kerja operasional yang terukur)..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-gray-500 focus:border-indigo-500 resize-none"></textarea>
        <button type="submit"
            class="mt-2 px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
            + Tambah CPMK
        </button>
        <span id="add-err" class="text-red-500 text-xs ml-3 hidden"></span>
    </form>
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

<p class="text-xs text-gray-500 mb-4">
    Masukkan persentase bobot tiap CPMK terhadap setiap CPL yang didukung.
    <strong>Total per kolom (CPL)</strong> harus sama dengan nilai <strong>Tabel 5</strong> untuk MK ini.
</p>

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
                        <input type="number" min="0" max="100" step="0.1"
                            value="{{ $bobot > 0 ? $bobot : '' }}"
                            placeholder="0"
                            data-cpmk="{{ $cpmk->id }}"
                            data-cpl="{{ $cpl->id }}"
                            onchange="saveBobot(this)"
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
                        {{ $rowTotal > 0 ? number_format($rowTotal, 1).'%' : '—' }}
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
                    {{ $ref > 0 ? number_format($ref, 1).'%' : '—' }}
                </td>
                @endforeach
                <td class="px-2 py-2 text-center text-xs text-emerald-600 font-semibold">
                    {{ number_format(array_sum($tabel5), 1) }}%
                </td>
            </tr>

            {{-- Baris: Total isian saat ini --}}
            <tr class="bg-gray-50 border-t border-gray-200">
                <td class="sticky left-0 bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-600 border-r border-gray-100">
                    Total Isian
                </td>
                @foreach($cpls as $cpl)
                @php
                    $col    = round($colTotals[$cpl->id] ?? 0, 1);
                    $ref    = $tabel5[$cpl->id] ?? 0;
                    $match  = $ref > 0 && abs($col - $ref) < 0.2;
                    $over   = $ref > 0 && $col > $ref + 0.2;
                    $colCls = $match ? 'text-green-600 font-bold'
                            : ($over  ? 'text-red-600 font-bold'
                            : ($col > 0 ? 'text-amber-600 font-semibold' : 'text-gray-300'));
                @endphp
                <td id="col-total-{{ $cpl->id }}" class="px-2 py-2 text-center text-xs {{ $colCls }}">
                    {{ $col > 0 ? number_format($col, 1).'%' : '—' }}
                    @if($over)
                        <span class="block text-[9px] font-normal leading-tight">⚠ melebihi</span>
                    @endif
                </td>
                @endforeach
                <td class="px-2 py-2 text-center text-xs text-gray-400">
                    {{ number_format(array_sum($colTotals), 1) }}%
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endif

</div><!-- /tab bobot -->
</div><!-- /x-data tabs -->

<script>
const CSRF = '{{ csrf_token() }}';

// ── Add CPMK ──────────────────────────────────────────────────────
document.getElementById('form-add-cpmk')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const desc = document.getElementById('new-deskripsi').value.trim();
    if (!desc) return;
    const err = document.getElementById('add-err');
    err.classList.add('hidden');

    const res = await fetch('{{ route("kurikulum.cpmk.store", $mataKuliah) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ deskripsi: desc }),
    });
    if (res.ok) {
        location.href = location.pathname + location.search + '#cpmk';
    } else {
        const data = await res.json();
        err.textContent = data.message || 'Gagal menyimpan.';
        err.classList.remove('hidden');
    }
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
            `<input type="number" min="0" max="100" step="0.1" value="" placeholder="0"
                data-cpmk="${cpmkId}" data-cpl="${cplId}" onchange="saveBobot(this)"
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
    span.textContent = t > 0 ? t.toFixed(1) + '%' : '—';

    // Perbarui juga kolom total (bottom rows) untuk setiap CPL yang terpengaruh
    refreshColTotals();
}

// Hitung ulang baris Total Isian (kolom per CPL) dari DOM
const TABEL5 = @json($tabel5 ?? []);

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

        cell.innerHTML = (sum > 0 ? sum.toFixed(1) + '%' : '—') +
            (over ? '<span class="block text-[9px] font-normal leading-tight">⚠ melebihi</span>' : '');
    });
}

// ── Save bobot ────────────────────────────────────────────────────
let bobotTimers = {};
function saveBobot(input) {
    const cpmkId = input.dataset.cpmk;
    const cplId  = input.dataset.cpl;
    const bobot  = parseFloat(input.value) || 0;

    input.classList.toggle('border-indigo-300', bobot > 0);
    input.classList.toggle('bg-gray-50', bobot > 0);

    clearTimeout(bobotTimers[`${cpmkId}-${cplId}`]);
    bobotTimers[`${cpmkId}-${cplId}`] = setTimeout(async () => {
        const res = await fetch(`/kurikulum/api/cpmk/${cpmkId}/bobot`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ cpl_id: cplId, bobot }),
        });
        if (res.ok) {
            const data = await res.json();
            updateBobotTotal(cpmkId, data.total_bobot);
            refreshColTotals();

            if (bobot > 0) {
                // Server set has_relation=true. Sync Tab 1 button if still unchecked.
                const btn = document.getElementById(`cpl-label-${cpmkId}-${cplId}`);
                if (btn && btn.dataset.checked !== '1') {
                    btn.dataset.checked = '1';
                    setCheckStyle(btn, true);
                }

                // If the Tab 2 cell lost its input during the debounce window
                // (e.g. concurrent toggleCpl uncheck), restore it with the saved value.
                const cell = document.getElementById(`bobot-cell-${cpmkId}-${cplId}`);
                if (cell && !cell.querySelector('input.bobot-input')) {
                    syncBobotCell(cpmkId, cplId, true);
                    const newInp = cell.querySelector('input');
                    if (newInp) newInp.value = bobot;
                }
            }
        }
    }, 500);
}
</script>
@endsection
