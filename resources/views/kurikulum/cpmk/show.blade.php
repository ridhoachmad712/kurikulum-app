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
<div class="flex gap-1 mb-6 border-b border-gray-200" x-data="{ tab: 'cpmk' }">
    <button @click="tab='cpmk'"
            :class="tab==='cpmk' ? 'bg-white border border-b-white border-gray-200 text-indigo-700 -mb-px' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition">
        Input CPMK & Keterkaitan CPL
    </button>
    <button @click="tab='bobot'"
            :class="tab==='bobot' ? 'bg-white border border-b-white border-gray-200 text-indigo-700 -mb-px' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition">
        Bobot % CPMK per CPL
    </button>

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
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
        <button type="submit"
            class="mt-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
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
@else

<p class="text-xs text-gray-500 mb-4">
    Masukkan persentase bobot tiap CPMK terhadap setiap CPL yang didukung.
    Total bobot per CPMK sebaiknya 100%.
</p>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-auto">
    <table class="text-xs w-full border-collapse">
        <thead class="bg-indigo-900 text-white">
            <tr>
                <th class="sticky left-0 bg-indigo-900 px-4 py-2.5 text-left min-w-[220px]">CPMK</th>
                @foreach($cpls as $cpl)
                <th class="px-3 py-2.5 text-center w-24">{{ $cpl->kode }}</th>
                @endforeach
                <th class="px-3 py-2.5 text-center w-20 bg-indigo-800">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpmks as $cpmk)
            <tr class="border-b border-gray-100 hover:bg-gray-50" id="bobot-row-{{ $cpmk->id }}">
                <td class="sticky left-0 bg-white px-4 py-2 border-r border-gray-100">
                    <span class="font-semibold text-indigo-700">{{ $cpmk->kode }}</span>
                    <p class="text-gray-500 text-xs mt-0.5 line-clamp-1">{{ $cpmk->deskripsi }}</p>
                </td>
                @foreach($cpls as $cpl)
                @php $bobot = $cpmkCplMap[$cpmk->id][$cpl->id]['bobot'] ?? 0; @endphp
                <td class="px-2 py-2 text-center">
                    <input type="number" min="0" max="100" step="0.1"
                        value="{{ $bobot > 0 ? $bobot : '' }}"
                        placeholder="0"
                        data-cpmk="{{ $cpmk->id }}"
                        data-cpl="{{ $cpl->id }}"
                        onchange="saveBobot(this)"
                        class="bobot-input w-20 border border-gray-200 rounded px-2 py-1 text-center text-xs
                               focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none
                               {{ $bobot > 0 ? 'border-indigo-300 bg-indigo-50' : '' }}">
                </td>
                @endforeach
                <td class="px-2 py-2 text-center font-bold" id="total-{{ $cpmk->id }}">
                    @php
                        $rowTotal = collect($cpmkCplMap[$cpmk->id] ?? [])->sum('bobot');
                    @endphp
                    <span class="{{ abs($rowTotal - 100) < 0.1 && $rowTotal > 0 ? 'text-green-600' : ($rowTotal > 0 ? 'text-amber-600' : 'text-gray-300') }}">
                        {{ $rowTotal > 0 ? number_format($rowTotal, 1).'%' : '—' }}
                    </span>
                </td>
            </tr>
            @endforeach
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
        location.reload();
    } else {
        const data = await res.json();
        err.textContent = data.message || 'Gagal menyimpan.';
        err.classList.remove('hidden');
    }
});

// ── Delete CPMK ───────────────────────────────────────────────────
async function deleteCpmk(id, kode) {
    if (!confirm(`Hapus ${kode}? Semua relasi CPL juga akan terhapus.`)) return;
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
async function toggleCpl(checkbox, cpmkId, cplId) {
    await fetch(`/kurikulum/api/cpmk/${cpmkId}/cpl-relation`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ cpl_id: cplId, has_relation: checkbox.checked }),
    });
    const badge = document.getElementById(`cpl-badge-${cpmkId}-${cplId}`);
    if (badge) badge.classList.toggle('hidden', !checkbox.checked);
}

// ── Save bobot ────────────────────────────────────────────────────
let bobotTimers = {};
function saveBobot(input) {
    const cpmkId = input.dataset.cpmk;
    const cplId  = input.dataset.cpl;
    const bobot  = parseFloat(input.value) || 0;

    input.classList.toggle('border-indigo-300', bobot > 0);
    input.classList.toggle('bg-indigo-50', bobot > 0);

    clearTimeout(bobotTimers[`${cpmkId}-${cplId}`]);
    bobotTimers[`${cpmkId}-${cplId}`] = setTimeout(async () => {
        const res = await fetch(`/kurikulum/api/cpmk/${cpmkId}/bobot`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ cpl_id: cplId, bobot }),
        });
        if (res.ok) {
            const data = await res.json();
            const totalEl = document.getElementById(`total-${cpmkId}`);
            if (totalEl) {
                const t = data.total_bobot;
                const cls = Math.abs(t - 100) < 0.1 && t > 0 ? 'text-green-600' : t > 0 ? 'text-amber-600' : 'text-gray-300';
                totalEl.querySelector('span').className = cls;
                totalEl.querySelector('span').textContent = t > 0 ? t.toFixed(1) + '%' : '—';
            }
        }
    }, 500);
}
</script>
@endsection
