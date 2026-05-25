@extends('layouts.app')
@section('title','Bobot Asesmen – '.$mataKuliah->nama)
@section('content')

<x-page-header
    title="Bobot Asesmen — {{ $mataKuliah->nama }}"
    subtitle="{{ $mataKuliah->kode_rps }} · {{ $mataKuliah->sks }} SKS · Semester {{ $mataKuliah->semester }}"
    back="{{ route('kurikulum.asesmen.index') }}"
    backLabel="Bobot Asesmen"/>

<div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-2.5 mb-5 text-xs text-blue-700 flex items-start gap-2">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    <span>
        Isi distribusi bobot metode asesmen per Sub-CPMK.
        <strong>Total per Sub-CPMK harus sama dengan bobot teoritis Sub-CPMK</strong>
        (= total bobot CPMK terhadap CPL ÷ jumlah Sub-CPMK).
        Acuan ditampilkan di kolom <em>Target</em>. Perubahan disimpan otomatis.
    </span>
</div>

@php
    $summTotal   = 0;
    $summFilled  = 0;
    $summSelaras = 0;
    foreach ($cpmks as $cpmk) {
        $meta        = $cpmkMeta[$cpmk->id] ?? [];
        $bobotPerSub = (float) ($meta['bobot_per_sub'] ?? 0);
        if ($bobotPerSub <= 0) continue;
        foreach ($cpmk->subCpmks as $sc) {
            $summTotal++;
            if ($sc->asesmen !== null) {
                $summFilled++;
                $t = (float) $sc->asesmen->total;
                if (abs($t - $bobotPerSub) <= 0.5) $summSelaras++;
            }
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
            <span class="text-[10px] text-gray-400 uppercase tracking-wide">Sub-CPMK</span>
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
        <p class="text-[10px] text-green-600 mt-1 font-medium">✓ Semua Sub-CPMK sudah selaras</p>
        @elseif($summFilled < $summTotal)
        <p class="text-[10px] text-gray-400 mt-1">{{ $summTotal - $summFilled }} Sub-CPMK belum diisi</p>
        @else
        <p class="text-[10px] text-amber-600 mt-1">{{ $summTotal - $summSelaras }} Sub-CPMK belum selaras</p>
        @endif
    </div>
</div>
@endif

@forelse($cpmks as $cpmk)
@if($cpmk->subCpmks->isNotEmpty())
@php
    $meta        = $cpmkMeta[$cpmk->id] ?? ['total_bobot' => 0, 'bobot_per_sub' => 0, 'sub_count' => 0];
    $bobotPerSub = $meta['bobot_per_sub'];
    $totalBobot  = $meta['total_bobot'];
    $noClo       = $cpmk->subCpmks->every(fn($sc) => $sc->subCpmkClos->isEmpty());
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">

    {{-- Header CPMK --}}
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-3 flex-wrap">
        <span class="font-semibold text-gray-700 text-sm">{{ $cpmk->kode }}</span>
        <span class="text-sm text-gray-600">{{ Str::limit($cpmk->deskripsi, 70) }}</span>

        {{-- Bobot info --}}
        @if($totalBobot > 0)
        <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 rounded-full text-xs text-emerald-700">
            Bobot CPMK: {{ number_format($totalBobot, 1) }}%
            @if($meta['sub_count'] > 1)
            → {{ number_format($bobotPerSub, 2) }}% / Sub-CPMK
            @endif
        </span>
        @else
        <span class="px-2 py-0.5 bg-amber-50 border border-amber-200 rounded-full text-xs text-amber-600">
            ⚠ Bobot CPMK–CPL belum diisi
        </span>
        @endif

        {{-- Warning: semua Sub-CPMK belum punya CLO --}}
        @if($noClo)
        <a href="{{ route('kurikulum.sub-cpmk.show', $cpmk) }}"
           class="px-2 py-0.5 bg-orange-50 border border-orange-200 rounded-full text-xs text-orange-600 hover:bg-orange-100 transition-colors">
            ⚠ Sub-CPMK belum memiliki CLO
        </a>
        @endif

        <a href="{{ route('kurikulum.sub-cpmk.show', $cpmk) }}"
           class="ml-auto inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-700 hover:underline shrink-0">
            Kelola Sub-CPMK
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left w-36">Sub-CPMK</th>
                    <th class="border-b border-gray-100 px-4 py-2.5 text-left">Deskripsi</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Tugas</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">Kuis</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-24">Kasus/Proyek</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UTS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-20">UAS</th>
                    <th class="border-b border-gray-100 px-3 py-2.5 text-center w-28">Total / Target</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($cpmk->subCpmks as $sc)
                @php
                    $a       = $sc->asesmen;
                    $total   = $a ? (float) $a->total : 0;
                    $target  = $subCpmkBobot[$sc->id] ?? 0;
                    $match   = $target > 0 && abs($total - $target) <= 0.5;
                    $over    = $target > 0 && $total > $target + 0.5;
                    $badgeCls = $match ? 'bg-green-100 text-green-700'
                              : ($over  ? 'bg-red-100 text-red-700'
                              : ($total > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400'));
                @endphp
                <tr class="hover:bg-gray-50" id="row-{{ $sc->id }}"
                    data-target="{{ $target }}">
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
                                   focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400">
                    </td>
                    @endforeach

                    <td class="px-3 py-2 text-center align-middle">
                        <span id="total-{{ $sc->id }}"
                              class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeCls }}">
                            {{ $total > 0 ? number_format($total, 1).'%' : '–' }}
                            @if($over) ⚠ @endif
                        </span>
                        @if($target > 0)
                        <p class="text-[10px] text-gray-400 mt-0.5">target: {{ number_format($target, 2) }}%</p>
                        @endif
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
const CSRF   = '{{ csrf_token() }}';
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
    const el     = document.getElementById('total-' + id);
    if (!el) return;
    const row    = document.getElementById('row-' + id);
    const target = parseFloat(row?.dataset.target ?? 0);
    const match  = target > 0 && Math.abs(total - target) <= 0.5;
    const over   = target > 0 && total > target + 0.5;

    el.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-semibold ' +
        (match ? 'bg-green-100 text-green-700' :
         over  ? 'bg-red-100 text-red-700'     :
         total > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400');

    el.textContent = (total > 0 ? total.toFixed(1) + '%' : '–') + (over ? ' ⚠' : '');
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
