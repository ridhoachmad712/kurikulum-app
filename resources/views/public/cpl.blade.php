@extends('layouts.public')
@section('title', 'CPL Program Studi')

@section('content')

{{-- Page header --}}
<div class="bg-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <nav class="flex items-center gap-1 text-xs text-blue-200 mb-2">
            <a href="{{ route('public.home') }}" class="hover:text-white transition">Beranda</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-blue-100">CPL</span>
        </nav>
        <h1 class="text-xl font-bold">Capaian Pembelajaran Lulusan (CPL)</h1>
        <p class="text-blue-100 text-sm mt-0.5">
            {{ $prodi->nama }} · {{ $cpls->count() }} CPL
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ─── RADAR CHART CARD ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-900">Peta Cakupan CPL</h2>
                <p class="text-xs text-gray-400 mt-0.5">Jumlah mata kuliah dan CPMK yang mendukung tiap CPL</p>
            </div>
            {{-- Toggle dataset --}}
            <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1" id="chart-toggle">
                <button data-mode="both" onclick="setChartMode('both')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-white text-blue-800 shadow-sm transition">
                    Keduanya
                </button>
                <button data-mode="mk" onclick="setChartMode('mk')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition">
                    Mata Kuliah
                </button>
                <button data-mode="cpmk" onclick="setChartMode('cpmk')"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition">
                    CPMK
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-0">

            {{-- Chart --}}
            <div class="flex-1 flex items-center justify-center p-6 min-h-[420px]">
                <div class="relative w-full">
                    <canvas id="cplRadarChart" style="max-height: 420px;"></canvas>
                </div>
            </div>

            {{-- Legend + Stats --}}
            <div class="lg:w-52 shrink-0 border-t lg:border-t-0 lg:border-l border-gray-100 p-5 self-stretch flex flex-col justify-between">

                {{-- Dataset legend --}}
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Keterangan</p>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-3 rounded-full shrink-0" style="background: rgba(99,102,241,0.7)"></span>
                            <span class="text-sm text-gray-700">Jumlah Mata Kuliah Pendukung</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-3 rounded-full shrink-0" style="background: rgba(16,185,129,0.7)"></span>
                            <span class="text-sm text-gray-700">Jumlah CPMK Terkait</span>
                        </div>
                    </div>

                    {{-- CPL badges + quick stats --}}
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Ringkasan CPL</p>
                    <div class="space-y-1 max-h-64 overflow-y-auto pr-1">
                        @foreach($cpls as $cpl)
                        <a href="#cpl-{{ $cpl->id }}"
                           class="flex items-center gap-2 group px-2 py-1.5 rounded-lg hover:bg-blue-50 transition">
                            <span class="shrink-0 w-8 h-8 rounded-lg bg-blue-100 text-blue-800 text-xs font-extrabold flex items-center justify-center group-hover:bg-blue-700 group-hover:text-white transition">
                                {{ $cpl->kode }}
                            </span>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-xs text-indigo-500 font-semibold">{{ $cpl->mk_count }} MK</span>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs text-emerald-500 font-semibold">{{ $cpl->cpmk_count }} CPMK</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Max values --}}
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-extrabold text-blue-800">{{ $chartMkData->max() }}</p>
                        <p class="text-xs text-blue-300 mt-0.5">Max MK / CPL</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-extrabold text-emerald-700">{{ $chartCpmkData->max() }}</p>
                        <p class="text-xs text-emerald-400 mt-0.5">Max CPMK / CPL</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── CPL CARDS ─────────────────────────────────────────── --}}
    <div class="space-y-4">
        @foreach($cpls as $cpl)
        <div id="cpl-{{ $cpl->id }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden scroll-mt-24">

            {{-- Header --}}
            <div class="px-6 py-5 flex items-start gap-4">
                <span class="shrink-0 w-12 h-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center font-extrabold text-sm shadow-sm">
                    {{ $cpl->kode }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 leading-snug">{{ $cpl->deskripsi }}</p>
                    @if($cpl->keterangan)
                    <p class="text-sm text-gray-500 mt-1">{{ $cpl->keterangan }}</p>
                    @endif
                    <div class="flex items-center gap-4 mt-2">
                        <span class="text-xs text-blue-700 font-semibold bg-blue-50 px-2 py-0.5 rounded-full">
                            {{ $cpl->mk_count }} Mata Kuliah
                        </span>
                        <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">
                            {{ $cpl->cpmk_count }} CPMK
                        </span>
                    </div>
                </div>

                {{-- Mini bar --}}
                <div class="shrink-0 hidden sm:flex flex-col gap-1.5 w-28">
                    <div>
                        <div class="flex justify-between text-xs mb-0.5">
                            <span class="text-gray-400">MK</span>
                            <span class="font-semibold text-blue-700">{{ $cpl->mk_count }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $chartMkData->max() > 0 ? round($cpl->mk_count / $chartMkData->max() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-0.5">
                            <span class="text-gray-400">CPMK</span>
                            <span class="font-semibold text-emerald-600">{{ $cpl->cpmk_count }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $chartCpmkData->max() > 0 ? round($cpl->cpmk_count / $chartCpmkData->max() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related MK --}}
            @if($cpl->mataKuliahs->isNotEmpty())
            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Mata Kuliah Pendukung</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($cpl->mataKuliahs->groupBy('semester')->sortKeys() as $smt => $mks)
                    @foreach($mks as $mk)
                    <a href="{{ route('public.mata-kuliah.show', $mk) }}"
                       class="group flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-1.5
                              hover:border-indigo-300 hover:shadow-sm transition">
                        <span class="font-mono text-xs text-blue-700 font-bold">{{ $mk->kode_rps }}</span>
                        <span class="text-xs text-gray-600 group-hover:text-gray-900 transition max-w-[160px] truncate">{{ $mk->nama }}</span>
                        <span class="text-xs text-gray-300 shrink-0">S{{ $mk->semester }}</span>
                    </a>
                    @endforeach
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach

        @if($cpls->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p>Belum ada CPL yang ditetapkan.</p>
        </div>
        @endif
    </div>

</div>

{{-- ─── CHART SCRIPT ──────────────────────────────────────────── --}}
<script>
// Data dari server
const CPL_LABELS   = @json($chartLabels);
const MK_DATA      = @json($chartMkData);
const CPMK_DATA    = @json($chartCpmkData);

// Warna
const COLOR_MK   = { border: 'rgba(99,102,241,1)',   bg: 'rgba(99,102,241,0.18)'  };
const COLOR_CPMK = { border: 'rgba(16,185,129,1)',   bg: 'rgba(16,185,129,0.15)'  };

let radarChart;

function buildDatasets(mode) {
    const mk = {
        label: 'Mata Kuliah',
        data: MK_DATA,
        borderColor: COLOR_MK.border,
        backgroundColor: COLOR_MK.bg,
        pointBackgroundColor: COLOR_MK.border,
        pointBorderColor: '#fff',
        pointRadius: 5,
        pointHoverRadius: 7,
        borderWidth: 2.5,
    };
    const cpmk = {
        label: 'CPMK',
        data: CPMK_DATA,
        borderColor: COLOR_CPMK.border,
        backgroundColor: COLOR_CPMK.bg,
        pointBackgroundColor: COLOR_CPMK.border,
        pointBorderColor: '#fff',
        pointRadius: 5,
        pointHoverRadius: 7,
        borderWidth: 2.5,
    };
    if (mode === 'mk')   return [mk];
    if (mode === 'cpmk') return [cpmk];
    return [mk, cpmk];
}

function initChart() {
    const ctx = document.getElementById('cplRadarChart').getContext('2d');

    radarChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: CPL_LABELS,
            datasets: buildDatasets('both'),
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: { duration: 500, easing: 'easeInOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17,24,39,0.92)',
                    titleColor: '#f3f4f6',
                    bodyColor: '#d1d5db',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        title: (items) => items[0].label,
                        label: (item) => ` ${item.dataset.label}: ${item.raw}`,
                    },
                },
            },
            scales: {
                r: {
                    min: 0,
                    ticks: {
                        stepSize: Math.ceil(Math.max(...MK_DATA, ...CPMK_DATA) / 5),
                        color: '#9ca3af',
                        backdropColor: 'transparent',
                        font: { size: 10 },
                    },
                    grid: {
                        color: 'rgba(156,163,175,0.2)',
                        circular: false,
                    },
                    angleLines: { color: 'rgba(156,163,175,0.25)' },
                    pointLabels: {
                        color: '#4338ca',
                        font: { size: 12, weight: 'bold' },
                    },
                },
            },
        },
    });
}

function setChartMode(mode) {
    // Update chart data
    radarChart.data.datasets = buildDatasets(mode);
    radarChart.update('active');

    // Update toggle button styles
    document.querySelectorAll('#chart-toggle button').forEach(btn => {
        const active = btn.dataset.mode === mode;
        btn.classList.toggle('bg-white',       active);
        btn.classList.toggle('text-blue-800',active);
        btn.classList.toggle('shadow-sm',      active);
        btn.classList.toggle('text-gray-500',  !active);
    });
}

// Load Chart.js from CDN then init
(function loadChartJs() {
    if (window.Chart) { initChart(); return; }
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
    s.onload = initChart;
    document.head.appendChild(s);
})();
</script>

@endsection
