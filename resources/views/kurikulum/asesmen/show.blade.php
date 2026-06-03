@extends('layouts.app')
@section('title','Bobot Asesmen – '.$mataKuliah->nama)
@section('content')

<x-page-header
    title="Bobot Asesmen — {{ $mataKuliah->nama }}"
    subtitle="{{ $mataKuliah->kode_rps }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}"
    back="{{ route('kurikulum.asesmen.index') }}"
    backLabel="Bobot Asesmen"/>

<x-dosen-stepper :step="4" :mataKuliah="$mataKuliah" />

<div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-2.5 mb-5 text-xs text-blue-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    <span>
        Isi distribusi metode penilaian (<strong>Tugas / Kuis / Kasus·Proyek / UTS / UAS</strong>)
        <strong>satu kali untuk tiap CPMK</strong>. Sistem otomatis membagi rata nilai tersebut ke seluruh
        Sub-CPMK di belakang layar. Usahakan jumlah metode sama dengan <em>target</em> (= total bobot CPMK terhadap CPL).
        Semua perubahan disimpan otomatis.
    </span>
</div>

@php
    $summTotal   = 0;   // jumlah CPMK yang punya bobot
    $summFilled  = 0;   // CPMK yang sudah diisi
    $summSelaras = 0;   // CPMK yang total metodenya = target
    foreach ($cpmks as $cpmk) {
        $meta = $cpmkMeta[$cpmk->id] ?? [];
        if ($cpmk->subCpmks->isEmpty()) continue;
        if (($meta['total_bobot'] ?? 0) <= 0) continue;
        $target = (int) ($meta['target'] ?? round($meta['total_bobot'] ?? 0));
        $summTotal++;
        $t = (float) ($cpmkAsesmen[$cpmk->id]['total'] ?? 0);
        if ($t > 0) {
            $summFilled++;
            if (abs($t - $target) <= 0.5) $summSelaras++;
        }
    }
    $summPct = $summTotal > 0 ? round($summSelaras / $summTotal * 100) : 0;
@endphp

@if($summTotal > 0)
<div class="bg-white border border-gray-100 rounded-xl shadow-sm px-5 py-4 mb-5 flex items-center gap-5 flex-wrap">
    {{-- Stats --}}
    <div class="flex items-center gap-4 text-sm flex-wrap flex-1 min-w-0">
        <div class="flex flex-col items-center px-4 py-1.5 bg-gray-50 rounded-lg min-w-[72px]">
            <span class="text-lg font-bold text-gray-800">{{ $summTotal }}</span>
            <span class="text-[10px] text-gray-400 uppercase tracking-wide">CPMK</span>
        </div>
        <div class="flex flex-col items-center px-4 py-1.5 bg-blue-50 rounded-lg min-w-[72px]">
            <span class="text-lg font-bold text-blue-700">{{ $summFilled }}</span>
            <span class="text-[10px] text-blue-400 uppercase tracking-wide">Diisi</span>
        </div>
        <div class="flex flex-col items-center px-4 py-1.5 {{ $summSelaras === $summTotal ? 'bg-green-50' : ($summSelaras > 0 ? 'bg-amber-50' : 'bg-gray-50') }} rounded-lg min-w-[72px]">
            <span class="text-lg font-bold {{ $summSelaras === $summTotal ? 'text-green-700' : ($summSelaras > 0 ? 'text-amber-700' : 'text-gray-400') }}">{{ $summSelaras }}</span>
            <span class="text-[10px] {{ $summSelaras === $summTotal ? 'text-green-400' : ($summSelaras > 0 ? 'text-amber-400' : 'text-gray-400') }} uppercase tracking-wide">Selaras</span>
        </div>
    </div>

    {{-- Progress --}}
    <div class="flex-1 min-w-[160px]">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-xs text-gray-500">Progress keselarasan</span>
            <span class="text-xs font-semibold {{ $summSelaras === $summTotal ? 'text-green-600' : ($summSelaras > 0 ? 'text-amber-600' : 'text-gray-400') }}">
                {{ $summPct }}%
            </span>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500
                        {{ $summSelaras === $summTotal ? 'bg-green-500' : ($summSelaras > 0 ? 'bg-amber-400' : 'bg-gray-200') }}"
                 style="width: {{ $summPct }}%"></div>
        </div>
        @if($summSelaras === $summTotal)
        <p class="text-[10px] text-green-600 mt-1 font-medium">✓ Semua CPMK sudah selaras</p>
        @elseif($summFilled < $summTotal)
        <p class="text-[10px] text-gray-400 mt-1">{{ $summTotal - $summFilled }} CPMK belum diisi</p>
        @else
        <p class="text-[10px] text-amber-600 mt-1">{{ $summTotal - $summSelaras }} CPMK belum selaras</p>
        @endif
    </div>
</div>
@endif

{{-- Toolbar simpan --}}
<div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
    <p id="dirty-hint" class="text-xs text-gray-400">
        Perubahan tersimpan otomatis. Klik <strong>Simpan</strong> untuk memastikan semua isian tersimpan.
    </p>
    <button type="button" id="btn-save-all" onclick="saveAll()"
            class="btn-primary text-sm shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="btn-save-all-label">Simpan</span>
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left w-40">CPMK</th>
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left">Deskripsi</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-28">Aktivitas Partisipatif</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Tugas</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Kuis</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-24">Kasus/Proyek</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UTS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UAS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-28">Total / Target</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($cpmks as $cpmk)
                @if($cpmk->subCpmks->isNotEmpty())
                @php
                    $meta    = $cpmkMeta[$cpmk->id] ?? ['total_bobot' => 0, 'sub_count' => 0, 'no_clo' => true];
                    $agg     = $cpmkAsesmen[$cpmk->id] ?? ['aktivitas_partisipatif'=>0,'tugas'=>0,'quis'=>0,'kasus_proyek'=>0,'uts'=>0,'uas'=>0,'total'=>0];
                    // Target = bobot CPMK yang sudah dibulatkan bersama (LRM) agar jumlah target = 100
                    $target  = $meta['total_bobot'] > 0 ? (int) ($meta['target'] ?? round($meta['total_bobot'])) : 0;
                    $total   = (float) $agg['total'];
                    $noClo   = $meta['no_clo'] ?? false;
                    $match   = $target > 0 && abs($total - $target) <= 0.5;
                    $over    = $target > 0 && $total > $target + 0.5;
                    $badgeCls = $match ? 'bg-green-100 text-green-700'
                              : ($over  ? 'bg-red-100 text-red-700'
                              : ($total > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400'));
                @endphp
                <tr class="hover:bg-gray-50" id="row-{{ $cpmk->id }}" data-target="{{ $target }}">
                    <td class="px-4 py-3 align-top">
                        <div class="font-semibold text-gray-700 text-sm">{{ $cpmk->kode }}</div>
                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                            @if($target > 0)
                            <span class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded-full text-[10px] text-gray-600">
                                Bobot {{ round($target) }}%
                            </span>
                            @else
                            <span class="px-1.5 py-0.5 bg-amber-50 border border-amber-200 rounded-full text-[10px] text-amber-600">
                                ⚠ Bobot CPMK–CPL belum diisi
                            </span>
                            @endif
                            <span class="px-1.5 py-0.5 bg-indigo-50 border border-indigo-200 rounded-full text-[10px] text-indigo-600">
                                {{ $meta['sub_count'] }} Sub-CPMK
                            </span>
                        </div>
                        @if($noClo)
                        <a href="{{ route('kurikulum.sub-cpmk.show', $mataKuliah) }}"
                           class="inline-block mt-1.5 px-1.5 py-0.5 bg-orange-50 border border-orange-200 rounded-full text-[10px] text-orange-600 hover:bg-orange-100 transition-colors">
                            ⚠ Sub-CPMK belum memiliki CLO
                        </a>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs align-top">{{ Str::limit($cpmk->deskripsi, 120) }}</td>

                    @foreach(['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'] as $field)
                    <td class="px-3 py-3 text-center align-middle">
                        <input type="number" min="0" max="100" step="1"
                            id="{{ $field }}-{{ $cpmk->id }}"
                            value="{{ $total > 0 ? round((float)$agg[$field]) : '' }}"
                            placeholder="0"
                            oninput="debounce({{ $cpmk->id }})"
                            class="w-16 text-center border border-gray-200 rounded-lg px-1.5 py-1 text-xs
                                   focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                    </td>
                    @endforeach

                    <td class="px-3 py-3 text-center align-middle">
                        <span id="total-{{ $cpmk->id }}"
                              class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeCls }}">
                            {{ $total > 0 ? round($total).'%' : '–' }}
                            @if($over) ⚠ @endif
                        </span>
                        @if($target > 0)
                        <p class="text-[10px] text-gray-400 mt-0.5">target: {{ round($target) }}%</p>
                        @endif
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="9" class="text-center py-16 text-gray-400">
                        Belum ada Sub-CPMK untuk mata kuliah ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @php
                $colTotals = ['aktivitas_partisipatif'=>0,'tugas'=>0,'quis'=>0,'kasus_proyek'=>0,'uts'=>0,'uas'=>0];
                foreach ($cpmks as $cpmk) {
                    if ($cpmk->subCpmks->isEmpty()) continue;
                    $agg = $cpmkAsesmen[$cpmk->id] ?? [];
                    foreach (array_keys($colTotals) as $f) {
                        $colTotals[$f] += (float) ($agg[$f] ?? 0);
                    }
                }
                $grandTotal = round(array_sum($colTotals), 2);
            @endphp
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-200 text-sm font-semibold text-gray-700">
                    <td class="px-4 py-3 align-middle" colspan="2">
                        <span class="text-xs uppercase tracking-wider text-gray-500">Total per Aspek Penilaian</span>
                    </td>
                    @foreach(['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'] as $field)
                    <td class="px-3 py-3 text-center align-middle">
                        <span id="coltotal-{{ $field }}"
                              class="inline-block min-w-[44px] px-2 py-0.5 rounded-lg bg-white border border-gray-200">
                            {{ round($colTotals[$field]) }}
                        </span>
                    </td>
                    @endforeach
                    <td class="px-3 py-3 text-center align-middle">
                        <span id="coltotal-grand"
                              class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                            {{ round($grandTotal) }}%
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ─── Matriks CPL terhadap Instrumen Penilaian ─────────────────── --}}
@php
    $cplInstr = ['aktivitas_partisipatif'=>0,'tugas'=>0,'quis'=>0,'kasus_proyek'=>0,'uts'=>0,'uas'=>0];
    foreach ($cplRows as $r) {
        foreach (array_keys($cplInstr) as $f) $cplInstr[$f] += $r[$f];
    }
    $cplGrand = round(array_sum($cplInstr), 2);
@endphp
<div class="mt-7">
    <div class="flex items-center gap-2 mb-1 flex-wrap">
        <h2 class="text-sm font-bold text-gray-800">Matriks CPL terhadap Instrumen Penilaian</h2>
        <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded-full text-[10px] text-gray-500">acuan bobot CPMK→CPL (Tabel 7)</span>
    </div>
    <p class="text-xs text-gray-400 mb-3">
        Bobot asesmen tiap CPMK diatribusikan ke CPL secara proporsional terhadap bobot CPMK→CPL. Diperbarui otomatis mengikuti isian di atas.
    </p>

    @if(empty($cplRows))
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>
            <p class="text-sm font-semibold text-amber-800">Matriks CPL belum bisa dihitung</p>
            <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                Tabel ini mengacu pada <strong>bobot CPMK terhadap CPL (Tabel 7)</strong>. Saat ini belum ada CPMK
                yang memiliki bobot CPMK→CPL, sehingga distribusi penilaian ke CPL belum dapat ditampilkan.
                Isi dulu bobotnya, lalu tabel ini akan terisi otomatis.
            </p>
            <a href="{{ route('kurikulum.cpmk.show', $mataKuliah) }}"
               class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-amber-800 hover:underline">
                Isi Bobot CPMK→CPL (Tabel 7)
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="border-b border-gray-100 px-4 py-2.5 text-left">CPL</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-28">Aktivitas Partisipatif</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Tugas</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Kuis</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-24">Kasus/Proyek</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UTS</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UAS</th>
                        <th class="border-b border-gray-100 px-3 py-2.5 text-center w-24">Total CPL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($cplRows as $cplId => $row)
                    <tr class="hover:bg-gray-50" id="cplrow-{{ $cplId }}">
                        <td class="px-4 py-3 align-top">
                            <span class="font-semibold text-gray-700 text-sm">{{ $row['kode'] }}</span>
                            <span class="block text-gray-500 text-xs mt-0.5">{{ Str::limit($row['nama'], 70) }}</span>
                        </td>
                        @foreach(['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'] as $field)
                        <td class="px-3 py-3 text-center align-middle text-gray-700 text-xs">
                            <span id="cplcell-{{ $cplId }}-{{ $field }}">{{ $row[$field] > 0 ? round($row[$field]).'%' : '–' }}</span>
                        </td>
                        @endforeach
                        <td class="px-3 py-3 text-center align-middle">
                            <span id="cpltotal-{{ $cplId }}"
                                  class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                {{ round($row['total']) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t-2 border-gray-200 text-sm font-semibold text-gray-700">
                        <td class="px-4 py-3 align-middle">
                            <span class="text-xs uppercase tracking-wider text-gray-500">Total Instrumen</span>
                        </td>
                        @foreach(['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'] as $field)
                        <td class="px-3 py-3 text-center align-middle">
                            <span id="cplinstr-{{ $field }}"
                                  class="inline-block min-w-[44px] px-2 py-0.5 rounded-lg bg-white border border-gray-200">
                                {{ round($cplInstr[$field]) }}%
                            </span>
                        </td>
                        @endforeach
                        <td class="px-3 py-3 text-center align-middle">
                            <span id="cplgrand"
                                  class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                {{ round($cplGrand) }}%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Status bar --}}
<div id="save-status" class="fixed bottom-4 right-4 text-xs px-3 py-1.5 rounded-full shadow hidden
    bg-white border border-gray-200 text-gray-600 transition-all"></div>

<style>
table input[type=number]::-webkit-inner-spin-button,
table input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
table input[type=number] { -moz-appearance: textfield; }
</style>

<script>
const CSRF   = '{{ csrf_token() }}';
const timers = {};
const pending = new Set();   // CPMK yang isiannya belum tersimpan ke server

const FIELDS = ['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'];
const CPL_SHARES = @json($cplShares ?? new stdClass);

function debounce(cpmkId) {
    clearTimeout(timers[cpmkId]);
    pending.add(String(cpmkId));      // tandai ada perubahan belum tersimpan
    updateDirtyHint();
    updateTotal(cpmkId);              // tampilkan total baris langsung saat mengetik
    updateColumnTotals();             // perbarui total per aspek (footer)
    updateCplTable();                 // perbarui matriks CPL
    showStatus('Mengetik...');
    timers[cpmkId] = setTimeout(() => saveCpmk(cpmkId), 500);
}

// Perbarui petunjuk & tombol simpan sesuai status perubahan
function updateDirtyHint() {
    const hint = document.getElementById('dirty-hint');
    const btn  = document.getElementById('btn-save-all');
    if (pending.size > 0) {
        if (hint) { hint.innerHTML = '<span class="text-amber-600 font-medium">● ' + pending.size + ' CPMK belum tersimpan</span> — klik Simpan.'; }
        if (btn)  { btn.classList.add('ring-2','ring-amber-300'); }
    } else {
        if (hint) { hint.innerHTML = 'Semua isian tersimpan. ✓'; }
        if (btn)  { btn.classList.remove('ring-2','ring-amber-300'); }
    }
}

// Metode sisa terbesar — bulatkan array nilai ke int yang jumlahnya = target
// (target default = round(jumlah)). Tie-break stabil per index, identik dgn PHP.
function largestRemainder(values, target) {
    if (!values.length) return [];
    const sum = values.reduce((s, v) => s + v, 0);
    if (target === undefined || target === null) target = Math.round(sum);

    const floors = values.map(v => Math.floor(v));
    const rema   = values.map((v, i) => v - floors[i]);
    let diff = target - floors.reduce((s, v) => s + v, 0);

    const order = values.map((_, i) => i).sort((a, b) => (rema[b] - rema[a]) || (a - b));
    if (diff > 0) {
        for (let i = 0; i < diff && i < order.length; i++) floors[order[i]] += 1;
    } else if (diff < 0) {
        for (let i = 0; i < -diff && i < order.length; i++) floors[order[order.length - 1 - i]] -= 1;
    }
    return floors;
}

// Recompute matriks CPL dari isian CPMK saat ini (atribusi proporsional bobot CPMK→CPL)
function updateCplTable() {
    const rows = Array.from(document.querySelectorAll('tr[id^="cplrow-"]'));
    if (!rows.length) return;

    const cplIds = rows.map(r => r.id.replace('cplrow-', ''));
    const acc = {};
    cplIds.forEach(id => acc[id] = { aktivitas_partisipatif:0, tugas:0, quis:0, kasus_proyek:0, uts:0, uas:0 });

    // Akumulasi nilai mentah (pecahan) per CPL dari atribusi tiap CPMK
    document.querySelectorAll('tr[id^="row-"]').forEach(row => {
        const cpmkId = row.id.replace('row-', '');
        const shares = CPL_SHARES[cpmkId];
        if (!shares) return;
        const vals = {};
        FIELDS.forEach(f => vals[f] = parseFloat(document.getElementById(f + '-' + cpmkId)?.value || 0) || 0);
        shares.forEach(s => {
            if (!acc[s.cpl_id]) return;
            FIELDS.forEach(f => acc[s.cpl_id][f] += vals[f] * s.share);
        });
    });

    // 1) Bulatkan total tiap CPL agar jumlahnya = round(grand total)
    const rawRowTotals = cplIds.map(id => FIELDS.reduce((s, f) => s + acc[id][f], 0));
    const intRowTotals = largestRemainder(rawRowTotals);

    // 2) Bulatkan 5 sel tiap CPL agar jumlahnya = total CPL yang sudah bulat
    const instr = { aktivitas_partisipatif:0, tugas:0, quis:0, kasus_proyek:0, uts:0, uas:0 };
    let grand = 0;
    cplIds.forEach((id, idx) => {
        const cellVals = FIELDS.map(f => acc[id][f]);
        const intCells = largestRemainder(cellVals, intRowTotals[idx]);
        FIELDS.forEach((f, i) => {
            const cell = document.getElementById('cplcell-' + id + '-' + f);
            if (cell) cell.textContent = intCells[i] > 0 ? intCells[i] + '%' : '–';
            instr[f] += intCells[i];
        });
        const tEl = document.getElementById('cpltotal-' + id);
        if (tEl) tEl.textContent = intRowTotals[idx] + '%';
        grand += intRowTotals[idx];
    });

    FIELDS.forEach(f => {
        const el = document.getElementById('cplinstr-' + f);
        if (el) el.textContent = instr[f] + '%';
    });
    const g = document.getElementById('cplgrand');
    if (g) g.textContent = grand + '%';
}

// Jumlahkan tiap kolom aspek penilaian dari seluruh CPMK (baris footer)
function updateColumnTotals() {
    let grand = 0;
    FIELDS.forEach(f => {
        let sum = 0;
        document.querySelectorAll('input[id^="' + f + '-"]').forEach(inp => {
            sum += parseFloat(inp.value || 0) || 0;
        });
        sum = Math.round(sum * 100) / 100;
        grand += sum;
        const el = document.getElementById('coltotal-' + f);
        if (el) el.textContent = Math.round(sum);
    });
    const g = document.getElementById('coltotal-grand');
    if (g) g.textContent = Math.round(grand) + '%';
}

// POST inti — kirim isian 1 CPMK; kembalikan true bila tersimpan
async function postCpmk(cpmkId) {
    const body = {};
    FIELDS.forEach(f => body[f] = parseFloat(document.getElementById(f + '-' + cpmkId)?.value || 0) || 0);
    const res = await fetch(`/kurikulum/api/cpmk/${cpmkId}/asesmen`, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    JSON.stringify(body),
    });
    if (!res.ok) return false;
    const json = await res.json().catch(() => ({}));
    return !!json.ok;
}

// Auto-save 1 CPMK (dipicu debounce)
async function saveCpmk(cpmkId) {
    showStatus('Menyimpan...');
    try {
        const ok = await postCpmk(cpmkId);
        if (ok) {
            pending.delete(String(cpmkId));
            updateDirtyHint();
            updateTotal(cpmkId);
            showStatus('✓ Tersimpan');
            setTimeout(() => hideStatus(), 2000);
        } else {
            showStatus('⚠ Gagal menyimpan — klik Simpan untuk mencoba lagi');
            setTimeout(() => hideStatus(), 4000);
        }
    } catch {
        showStatus('⚠ Tidak dapat terhubung ke server — coba klik Simpan / refresh');
        setTimeout(() => hideStatus(), 4000);
    }
}

// Simpan SEMUA CPMK secara eksplisit (tombol Simpan)
async function saveAll() {
    const btn   = document.getElementById('btn-save-all');
    const label = document.getElementById('btn-save-all-label');
    // batalkan timer auto-save yang masih menunggu — kita simpan langsung
    Object.values(timers).forEach(t => clearTimeout(t));

    const ids = Array.from(document.querySelectorAll('tr[id^="row-"]')).map(r => r.id.replace('row-', ''));
    if (!ids.length) return;

    if (btn) btn.disabled = true;
    if (label) label.textContent = 'Menyimpan...';
    showStatus('Menyimpan semua...');

    let ok = 0, fail = 0;
    for (const id of ids) {
        try {
            if (await postCpmk(id)) { ok++; pending.delete(String(id)); updateTotal(id); }
            else { fail++; }
        } catch { fail++; }
    }

    updateColumnTotals();
    updateCplTable();
    updateDirtyHint();

    if (btn) btn.disabled = false;
    if (label) label.textContent = 'Simpan';

    if (fail === 0) {
        showStatus('✓ Semua tersimpan (' + ok + ' CPMK)');
    } else {
        showStatus('⚠ ' + fail + ' CPMK gagal disimpan — coba lagi');
    }
    setTimeout(() => hideStatus(), 3500);
}

// Peringatan bila menutup/pindah halaman dengan perubahan belum tersimpan
window.addEventListener('beforeunload', function (e) {
    if (pending.size > 0) { e.preventDefault(); e.returnValue = ''; }
});

// Hitung ulang total baris (dari 5 input metode) + perbarui badge vs target
function updateTotal(cpmkId) {
    const el = document.getElementById('total-' + cpmkId);
    if (!el) return;
    let total = 0;
    ['aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'].forEach(f => {
        total += parseFloat(document.getElementById(f + '-' + cpmkId)?.value || 0) || 0;
    });
    total = Math.round(total * 100) / 100;

    const row    = document.getElementById('row-' + cpmkId);
    const target = parseFloat(row?.dataset.target ?? 0);
    const match  = target > 0 && Math.abs(total - target) <= 0.5;
    const over   = target > 0 && total > target + 0.5;

    el.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold ' +
        (match ? 'bg-green-100 text-green-700' :
         over  ? 'bg-red-100 text-red-700'     :
         total > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400');

    el.textContent = (total > 0 ? Math.round(total) + '%' : '–') + (over ? ' ⚠' : '');
}

function showStatus(msg) {
    const el = document.getElementById('save-status');
    el.textContent = msg;
    el.classList.remove('hidden');
}
function hideStatus() {
    document.getElementById('save-status')?.classList.add('hidden');
}

// Hitung total semua baris saat halaman dimuat (sesuai data tersimpan)
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr[id^="row-"]').forEach(function (row) {
        updateTotal(row.id.replace('row-', ''));
    });
    updateColumnTotals();
    updateCplTable();
    updateDirtyHint();
});
</script>

@hasrole('dosen')
<div class="mt-6 flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
    <a href="{{ route('kurikulum.asesmen.index') }}" class="btn-secondary text-sm">
        ← Kembali ke daftar MK
    </a>
    <a href="{{ route('dashboard') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Selesai — Kembali ke Dashboard
    </a>
</div>
@endhasrole

@endsection

@section('help')
<div class="space-y-4">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Satu Isian per CPMK</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Anda cukup mengisi distribusi metode penilaian (Tugas, Kuis, Kasus/Proyek, UTS, UAS) <strong>satu kali untuk tiap CPMK</strong>. Tidak perlu lagi mengisi per Sub-CPMK.</p>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Dibagi Rata Otomatis</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Nilai yang Anda isi otomatis dibagi rata ke seluruh Sub-CPMK CPMK tersebut, sehingga perhitungan pengukuran &amp; akreditasi tetap berjalan seperti biasa.</p>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Indikator Selaras</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Kolom <strong>Total / Target</strong> menampilkan jumlah bobot yang telah diisi. Hijau berarti sudah selaras dengan target (= total bobot CPMK terhadap CPL); kuning berarti belum pas; merah berarti melebihi target.</p>
    </div>
    <div class="bg-blue-50 rounded-xl px-4 py-3">
        <p class="text-xs text-blue-700 font-medium">💡 Tips</p>
        <p class="text-xs text-blue-600 mt-1 leading-relaxed">Semua perubahan tersimpan otomatis — tidak perlu klik tombol Simpan.</p>
    </div>
</div>
@endsection
