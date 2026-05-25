@extends('layouts.app')
@section('title','Pengukuran CPMK – '.$mataKuliah->nama)
@section('content')

<x-page-header
    title="Pengukuran CPMK — {{ $mataKuliah->nama }}"
    subtitle="{{ $mataKuliah->kode_rps }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}"
    back="{{ route('kurikulum.pengukuran.cpmk') }}"
    backLabel="Pengukuran CPMK"/>

@hasrole('dosen')
<div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5 mb-4 text-xs text-indigo-700">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>Halaman ini hanya dapat diakses untuk mata kuliah yang ditugaskan kepada Anda.</span>
</div>
@endhasrole

{{-- Info box --}}
<div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 mb-5 text-xs text-amber-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>
    <span>
        Total bobot instrumen per baris harus <strong>= Bobot Sub-CPMK</strong> (bukan 100%).
        Bobot Sub-CPMK = Total Bobot CPMK ÷ Jumlah Sub-CPMK dalam CPMK tersebut.
        Perubahan disimpan otomatis saat Anda berhenti mengetik.
    </span>
</div>

@php
    // Kumpulkan bobot target per sub_cpmk_id → untuk JS
    $bobotTargets = [];
    foreach ($cpmks as $cpmk) {
        foreach ($cpmk->subCpmks as $sc) {
            $bobotTargets[$sc->id] = (float) $cpmk->bobot_per_sub;
        }
    }
@endphp

@forelse($cpmks as $cpmk)
@if($cpmk->subCpmks->isNotEmpty())
@php
    $instruments = ['tugas' => 'Tugas', 'quis' => 'Kuis', 'kasus_proyek' => 'Kasus/Proyek', 'uts' => 'UTS', 'uas' => 'UAS'];
    // Hitung total per kolom (instrumen) untuk CPMK ini
    $colTotals = [];
    foreach (array_keys($instruments) as $f) {
        $colTotals[$f] = $cpmk->subCpmks->sum(fn($sc) => $sc->asesmen ? (float)$sc->asesmen->{$f} : 0);
    }
    $colGrandTotal = array_sum($colTotals);
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">

    {{-- CPMK header --}}
    <div class="bg-gray-800 text-white px-4 py-2.5 flex items-center gap-3">
        <span class="font-semibold text-sm">{{ $cpmk->kode }}</span>
        <span class="text-gray-300 text-sm">{{ Str::limit($cpmk->deskripsi, 80) }}</span>
        <div class="ml-auto flex items-center gap-4 text-xs">
            <span class="text-gray-400">
                Bobot CPMK: <span class="text-white font-semibold">{{ number_format($cpmk->total_bobot, 1) }}%</span>
            </span>
            <span class="text-gray-400">
                Bobot/Sub: <span class="text-amber-300 font-semibold">{{ number_format($cpmk->bobot_per_sub, 2) }}%</span>
            </span>
        </div>
    </div>

    @if($cpmk->bobot_per_sub <= 0)
    <div class="px-4 py-3 bg-amber-50 border-b border-amber-100">
        <p class="text-xs text-amber-600">
            ⚠ Bobot CPMK ini belum diisi di Tabel 7. Isi bobot CPMK terlebih dahulu agar batas asesmen dapat dihitung.
        </p>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="text-xs w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-200 px-3 py-2 text-left min-w-[180px] text-gray-600">Sub-CPMK</th>
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-amber-50 text-amber-700 font-semibold">Target</th>
                    @foreach($instruments as $key => $label)
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">{{ $label }}</th>
                    @endforeach
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 bg-emerald-50 text-emerald-700 font-semibold">Total</th>
                    <th class="border border-gray-200 px-2 py-2 text-center w-20 text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cpmk->subCpmks as $sc)
                @php
                    $a        = $sc->asesmen;
                    $target   = (float) $cpmk->bobot_per_sub;
                    $noTarget = $target <= 0;
                    $total    = $a ? (float) $a->total : 0;
                    $selaras  = !$noTarget && abs($total - $target) <= 0.5;
                @endphp
                <tr class="hover:bg-gray-50/50 {{ $noTarget ? 'opacity-60' : '' }}" id="row-{{ $sc->id }}">
                    <td class="border border-gray-200 px-3 py-2">
                        <span class="font-semibold text-gray-700">{{ $sc->kode }}</span>
                        <p class="text-gray-400 mt-0.5 text-[10px] leading-tight">{{ Str::limit($sc->deskripsi, 60) }}</p>
                    </td>
                    <td class="border border-gray-200 px-2 py-2 text-center bg-amber-50 font-bold text-amber-700">
                        {{ $target > 0 ? number_format($target, 2) : '—' }}
                    </td>
                    @foreach(array_keys($instruments) as $field)
                    <td class="border border-gray-200 px-2 py-2 text-center {{ $noTarget ? 'bg-gray-50' : '' }}">
                        <input type="number" min="0" max="100" step="0.01"
                               id="{{ $field }}-{{ $sc->id }}"
                               value="{{ $a ? (float)$a->{$field} : '' }}"
                               placeholder="0"
                               oninput="onInput({{ $sc->id }}, {{ $target }})"
                               {{ $noTarget ? 'disabled' : '' }}
                               class="w-16 text-center border border-gray-200 rounded-lg px-1.5 py-1 text-xs
                                      {{ $noTarget
                                          ? 'bg-gray-100 text-gray-300 cursor-not-allowed'
                                          : 'focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 '.($a ? '' : 'bg-gray-50') }}">
                    </td>
                    @endforeach
                    <td class="border border-gray-200 px-2 py-2 text-center bg-emerald-50 font-bold">
                        <span id="total-{{ $sc->id }}"
                              class="{{ $selaras ? 'text-emerald-700' : ($total > 0 ? 'text-amber-700' : 'text-gray-300') }}">
                            {{ $total > 0 ? number_format($total, 2) : '—' }}
                        </span>
                    </td>
                    <td class="border border-gray-200 px-2 py-2 text-center">
                        <span id="status-{{ $sc->id }}">
                            @if($selaras)
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">✓ Selaras</span>
                            @elseif($total > 0 && $target > 0)
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">
                                {{ $total > $target ? '+' : '' }}{{ number_format($total - $target, 2) }}
                            </span>
                            @elseif($target <= 0)
                            <span class="text-gray-300">—</span>
                            @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-400">Kosong</span>
                            @endif
                        </span>
                    </td>
                </tr>
                @endforeach

                {{-- Total per kolom --}}
                <tr class="bg-gray-800 text-white font-bold">
                    <td class="border border-gray-700 px-3 py-2">Total Kolom</td>
                    <td class="border border-gray-700 px-2 py-2 text-center bg-amber-900 text-amber-300">
                        {{ number_format($cpmk->total_bobot, 2) }}
                    </td>
                    @foreach(array_keys($instruments) as $field)
                    <td class="border border-gray-700 px-2 py-2 text-center text-xs" id="col-{{ $cpmk->id }}-{{ $field }}">
                        {{ $colTotals[$field] > 0 ? number_format($colTotals[$field], 2) : '—' }}
                    </td>
                    @endforeach
                    <td class="border border-gray-700 px-2 py-2 text-center bg-emerald-900 text-emerald-300">
                        {{ $colGrandTotal > 0 ? number_format($colGrandTotal, 2) : '—' }}
                    </td>
                    <td class="border border-gray-700 px-2 py-2"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif
@empty
<div class="text-center py-16 text-gray-400">
    <p>Belum ada CPMK dan Sub-CPMK untuk mata kuliah ini.</p>
</div>
@endforelse

{{-- Status indicator --}}
<div id="save-status"
     class="fixed bottom-6 right-6 text-xs px-3 py-1.5 rounded-full shadow-lg hidden
            bg-white border border-gray-200 text-gray-600 transition-all z-50"></div>

<script>
const CSRF         = '{{ csrf_token() }}';
const FIELDS       = ['tugas','quis','kasus_proyek','uts','uas'];
const bobotTargets = @json($bobotTargets);
const timers       = {};

function onInput(id, target) {
    updateRowTotal(id, target);
    clearTimeout(timers[id]);
    showStatus('Mengetik...');
    timers[id] = setTimeout(() => saveRow(id), 600);
}

function updateRowTotal(id, target) {
    let total = 0;
    for (const f of FIELDS) {
        total += parseFloat(document.getElementById(f + '-' + id)?.value || 0) || 0;
    }
    total = Math.round(total * 100) / 100;

    const totalEl  = document.getElementById('total-' + id);
    const statusEl = document.getElementById('status-' + id);
    if (!totalEl || !statusEl) return;

    totalEl.textContent = total > 0 ? total.toFixed(2) : '—';

    const diff    = Math.round((total - target) * 100) / 100;
    const selaras = target > 0 && Math.abs(diff) <= 0.5;

    if (selaras) {
        totalEl.className = 'text-emerald-700';
        statusEl.innerHTML = '<span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">✓ Selaras</span>';
    } else if (total > 0 && target > 0) {
        totalEl.className = 'text-amber-700';
        const sign = diff > 0 ? '+' : '';
        statusEl.innerHTML = `<span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">${sign}${diff.toFixed(2)}</span>`;
    } else if (target <= 0) {
        totalEl.className = 'text-gray-300';
        statusEl.innerHTML = '<span class="text-gray-300">—</span>';
    } else {
        totalEl.className = 'text-gray-300';
        statusEl.innerHTML = '<span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-400">Kosong</span>';
    }
}

async function saveRow(id) {
    const body = {};
    for (const f of FIELDS) {
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
            showStatus('✓ Tersimpan');
            setTimeout(hideStatus, 2000);
        } else {
            showStatus('⚠ Gagal menyimpan');
        }
    } catch {
        showStatus('⚠ Gagal menyimpan');
    }
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
