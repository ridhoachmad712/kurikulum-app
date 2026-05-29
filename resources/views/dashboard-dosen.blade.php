@extends('layouts.app')
@section('title','Dashboard – Tugas Saya')
@section('content')

{{-- ── GREETING ── --}}
@php
    $jam       = now()->hour;
    $salam     = $jam < 11 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
    $namaDepan   = auth()->user()->name ?? 'Pengguna';
    $totalScore  = $mataKuliahs->sum('score');
    $maxScore    = $mataKuliahs->count() * 4;
    $pct         = $maxScore > 0 ? round($totalScore / $maxScore * 100) : 0;

    $quotes = [
        'Menyambungkan CPMK ke CPL tak sesulit memahami isi kepala mahasiswa. Semangat! 🧩',
        'Asesor akreditasi memantau kolom kosong ini dari kejauhan. 👀',
        'Skripsi mahasiswa saja sanggup Anda coret-coret berkali-kali, masa isi form kurikulum menyerah? 📑',
        'Mata kuliah yang kosong rentan diisi oleh harapan-harapan palsu. 🌬️',
        'Kurikulum ini tidak akan selesai dengan hanya tatapan kosong tanpa makna. 👁️',
        'Maaf, aplikasi ini belum memiliki fitur auto-kerjain. ⌨️',
        'Isian yang lengkap menjauhkan Anda dari pesan WhatsApp ketua prodi yang menanyakan progress. 📱',
        'Ingat, setiap baris kurikulum yang Anda isi hari ini adalah investasi untuk me-time esok hari. 🧘‍♂️',
        'Deadline itu seperti mantan, makin dihindari makin kepikiran. 😭',
        'Cukup otak mahasiswamu yang kosong, matriksmu jangan. 😭',
        'Kurikulum yang rapi adalah bentuk cintamu kepada prodi. 💙',
        'Gelas kopi sudah kosong, tapi form mata kuliah masih putih bersih. Sungguh sebuah tragedi. 🫗',
        'Sistem mendeteksi kadar kafein Anda mulai menipis. Segera seduh kopi sebelum lanjut mengetik CPMK. 🪫',
        'Satu teguk kopi = satu baris deskripsi mata kuliah. Ayo, jangan berhenti ngopi! ⌨️',
        'Semakin pahit kopi yang Anda minum hari ini, semakin manis masa depan mahasiswa kita. 🎓',
        'Barang siapa yang mengerjakan dengan ikhlas, niscaya akan dijauhkan dari mahasiswa plenger. 😇',
    ];
    $quoteHari = $quotes[array_rand($quotes)];
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-7 pb-5 border-b border-gray-100">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Selamat {{ $salam }}, {{ $namaDepan }}! 👋</h1>
        <p class="text-base text-gray-800 mt-1">{{ $quoteHari }}</p>
        <p class="text-base text-gray-400 mt-1">
            @if($summary['total_mk'] == 0)
                Anda belum memiliki penugasan mata kuliah.
            @elseif($summary['mk_pending'] == 0)
                Semua mata kuliah Anda sudah lengkap. Kerja bagus! 🎉
            @else
                Masih ada
                <span class="font-medium text-amber-600">{{ $summary['mk_pending'] }} mata kuliah</span>
                yang belum dilengkapi.
            @endif
        </p>
    </div>
    <div class="shrink-0 text-xs text-gray-400 sm:text-right">
        <p class="font-medium text-gray-500">{{ now()->format('d M Y') }}</p>
        <p>{{ now()->format('H:i') }} WITA</p>
    </div>
</div>

{{-- ── INFO PANEL ── --}}
<div x-data="{ show: !localStorage.getItem('dosen_info_dismissed') }" x-show="show" x-cloak
     class="mb-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4 flex gap-3 items-start">
    <svg class="shrink-0 w-5 h-5 text-blue-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
    </svg>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-blue-800">Tentang Aplikasi Ini</p>
        <p class="text-sm text-blue-700 mt-0.5 leading-relaxed">
            Aplikasi ini membantu Anda melengkapi dokumen kurikulum untuk setiap mata kuliah yang diampu.
            Terdapat <strong>4 langkah</strong> yang perlu diselesaikan per mata kuliah:
            Matriks MK–CPL, CPMK, Sub-CPMK, dan Bobot Asesmen.
        </p>
    </div>
    <button @click="show = false; localStorage.setItem('dosen_info_dismissed', '1')"
            class="shrink-0 text-blue-300 hover:text-blue-500 transition-colors" title="Tutup">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- ── RINGKASAN CARD ── --}}
@if($summary['total_mk'] > 0)
@php
    $mkSelesai  = $mataKuliahs->where('score', 4)->count();
    $mkProgres  = $mataKuliahs->where('score', '>', 0)->where('score', '<', 4)->count();
    $mkBelum    = $mataKuliahs->where('score', 0)->count();
    $mkTerdekat = $mataKuliahs->where('score', '<', 4)->sortByDesc('score')->first();
    $sisaLangkah = $maxScore - $totalScore;
@endphp
<div class="card p-5 mb-7">

    {{-- Baris atas: progress bar + persentase --}}
    <div class="flex items-center justify-between gap-4 mb-3">
        <div class="flex-1">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Progress Kelengkapan Kurikulum</p>
            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                <div class="h-2.5 rounded-full transition-all duration-500 {{ $pct == 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>
        <p class="text-2xl font-bold shrink-0 {{ $pct == 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $pct }}%</p>
    </div>

    {{-- Baris bawah: 3 kolom info --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-gray-100">

        {{-- Kolom 1: Status breakdown --}}
        <div class="flex flex-col gap-1.5">
            <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $totalScore }}/{{ $maxScore }} langkah terisi</p>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
                <span class="text-sm text-gray-700">{{ $mkSelesai }} mata kuliah selesai</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                <span class="text-sm text-gray-700">{{ $mkProgres }} sedang berjalan</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-gray-200 shrink-0"></span>
                <span class="text-sm text-gray-700">{{ $mkBelum }} belum dimulai</span>
            </div>
        </div>

        {{-- Kolom 2: Langkah tersisa --}}
        <div class="flex flex-col justify-center sm:border-l sm:border-gray-100 sm:pl-4">
            <p class="text-xs text-gray-400 font-medium mb-1">Langkah tersisa</p>
            @if($sisaLangkah == 0)
                <p class="text-sm font-semibold text-green-600">Semua langkah selesai! 🎉</p>
            @else
                <p class="text-2xl font-bold text-gray-800">{{ $sisaLangkah }}</p>
                <p class="text-xs text-gray-400">langkah belum terisi</p>
            @endif
        </div>

        {{-- Kolom 3: MK paling dekat selesai --}}
        <div class="flex flex-col justify-center sm:border-l sm:border-gray-100 sm:pl-4">
            <p class="text-xs text-gray-400 font-medium mb-1">Prioritaskan berikutnya</p>
            @if($mkTerdekat)
            @php
                $allAsesMkT = $mkTerdekat->sub_cpmks_count > 0
                           && $mkTerdekat->sub_asesmen_count == $mkTerdekat->sub_cpmks_count;
                $nextHref = match(true) {
                    !$mkTerdekat->has_matriks          => route('kurikulum.matriks.input'),
                    $mkTerdekat->cpmks_count == 0      => route('kurikulum.cpmk.show', $mkTerdekat),
                    $mkTerdekat->sub_cpmks_count == 0  => route('kurikulum.sub-cpmk.show', $mkTerdekat),
                    !$allAsesMkT                       => route('kurikulum.asesmen.show', $mkTerdekat),
                    default                            => route('kurikulum.cpmk.show', $mkTerdekat),
                };
            @endphp
                <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-1">{{ $mkTerdekat->nama }}</p>
                <div class="flex items-center justify-between mt-1.5">
                    <span class="text-xs text-amber-600">{{ $mkTerdekat->score }}/4 langkah</span>
                    <a href="{{ $nextHref }}"
                       class="text-xs font-semibold text-gray-700 hover:text-gray-900 hover:underline">
                        Lanjutkan →
                    </a>
                </div>
            @else
                <p class="text-sm text-green-600 font-medium">Tidak ada MK tertunda 🎉</p>
            @endif
        </div>

    </div>
</div>
@endif

{{-- ── MK CARDS — grouped by semester ── --}}
@if($mataKuliahs->isEmpty())
<div class="card p-12 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <p class="font-semibold text-gray-500">Belum ada penugasan</p>
    <p class="text-sm text-gray-400 mt-1">Hubungi Kaprodi untuk mendapatkan mata kuliah yang perlu diisi.</p>
</div>
@else

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
@foreach($mataKuliahs as $mk)
@php
    $allAses = $mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count;

    /*
     * Checklist dengan label angka granular:
     *   label     = teks item
     *   done      = apakah langkah selesai
     *   detail    = angka progres (opsional)
     *   href      = link aksi
     */
    $firstCpmk = $firstCpmkByMk[$mk->id] ?? null;

    $checks = [
        [
            'label'  => 'Matriks MK–CPL',
            'done'   => $mk->has_matriks,
            'detail' => $mk->has_matriks ? 'sudah terhubung' : 'belum diisi',
            'href'   => route('kurikulum.matriks.input'),
        ],
        [
            'label'  => 'CPMK',
            'done'   => $mk->cpmks_count > 0,
            'detail' => $mk->cpmks_count > 0 ? $mk->cpmks_count . ' CPMK' : 'belum ada',
            'href'   => route('kurikulum.cpmk.show', $mk),
        ],
        [
            'label'  => 'Sub-CPMK',
            'done'   => $mk->sub_cpmks_count > 0,
            'detail' => $mk->sub_cpmks_count > 0 ? $mk->sub_cpmks_count . ' Sub-CPMK' : 'belum ada',
            'href'   => route('kurikulum.sub-cpmk.show', $mk),
        ],
        [
            'label'  => 'Bobot Asesmen',
            'done'   => $allAses,
            'detail' => $mk->sub_cpmks_count > 0
                            ? $mk->sub_asesmen_count . '/' . $mk->sub_cpmks_count . ' sub-CPMK terisi'
                            : 'belum ada Sub-CPMK',
            'href'   => route('kurikulum.asesmen.show', $mk),
        ],
    ];

    // Langkah berikutnya yang belum selesai
    $nextStep = collect($checks)->first(fn($s) => !$s['done']);

    $barPct   = round($mk->score / 4 * 100);
    $barColor = $mk->score == 4 ? 'bg-green-500' : 'bg-amber-400';

    $badgeClass = match(true) {
        $mk->score == 4 => 'bg-green-100 text-green-700',
        $mk->score == 0 => 'bg-gray-100 text-gray-400',
        default         => 'bg-amber-100 text-amber-700',
    };
    $badgeLabel = match($mk->score) {
        4       => '✓ Lengkap',
        0       => 'Belum mulai',
        default => ($nextStep ? $nextStep['label'] : "Langkah {$mk->score}/4"),
    };

    $borderClass = match(true) {
        $mk->score == 4 => 'border-l-4 border-l-green-400',
        $mk->score == 0 => '',
        default         => 'border-l-4 border-l-amber-400',
    };
@endphp

<div class="card flex flex-col overflow-hidden {{ $borderClass }}">

    {{-- Card header --}}
    <div class="px-5 pt-4 pb-3">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="font-mono text-xs text-gray-700 font-semibold">{{ $mk->kode_rps }}</span>
                    @if($mk->sks)
                    <span class="text-xs text-gray-400">· {{ $mk->sks }} SKS</span>
                    @endif
                </div>
                <p class="font-semibold text-gray-800 text-sm leading-snug">{{ $mk->nama }}</p>
            </div>
            <span class="shrink-0 inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                {{ $badgeLabel }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="flex items-center gap-2.5">
            <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500"
                     style="width: {{ $barPct }}%"></div>
            </div>
            <span class="text-xs font-semibold text-gray-500 shrink-0">{{ $mk->score }}/4</span>
        </div>
    </div>

    {{-- Checklist: 5 langkah dengan detail angka --}}
    <div class="px-5 py-3 border-t border-gray-50 space-y-2">
        @php $pendingNo = 0; @endphp
        @foreach($checks as $step)
        @php if (!$step['done']) $pendingNo++; @endphp
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                @if($step['done'])
                    <span class="shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span class="text-xs text-gray-400">{{ $step['label'] }}</span>
                @else
                    <span class="shrink-0 w-5 h-5 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center">
                        <span class="text-[9px] font-bold text-amber-500">{{ $pendingNo }}</span>
                    </span>
                    <a href="{{ $step['href'] }}"
                       class="text-xs text-gray-700 hover:text-gray-900 font-medium hover:underline truncate">
                        {{ $step['label'] }}
                    </a>
                @endif
            </div>
            {{-- Detail angka di kanan --}}
            <span class="shrink-0 text-xs {{ $step['done'] ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                {{ $step['detail'] }}
            </span>
        </div>
        @endforeach
    </div>

    {{-- Card footer: quick action --}}
    <div class="px-5 py-3 mt-auto border-t border-gray-50 flex items-center justify-between gap-3">
        @if($mk->score == 4)
            <span class="text-xs text-green-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Semua langkah selesai
            </span>
            <a href="{{ route('kurikulum.asesmen.show', $mk) }}"
               class="text-xs text-gray-400 hover:text-gray-700 hover:underline">Lihat asesmen →</a>
        @else
            <p class="text-xs text-gray-400 truncate">
                Lanjutkan:
                <span class="font-medium text-gray-600">{{ $nextStep['label'] }}</span>
            </p>
            <a href="{{ $nextStep['href'] }}" class="btn-primary btn-sm shrink-0">
                Isi sekarang
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

</div>
@endforeach
</div>{{-- end grid --}}

@endif

@endsection

@section('navbar_version', 'Sistem Informasi Kurikulum versi 1.2.7')
