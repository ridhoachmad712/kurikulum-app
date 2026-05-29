@extends('layouts.app')
@section('title','Dashboard Kelengkapan Kurikulum')
@section('content')

@php
    $tot    = $summary['total_mk'] ?: 1;
    $totSub = $summary['total_sub'] ?: 1;

    $jam        = now()->hour;
    $salam      = $jam < 11 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
    $namaDepan  = auth()->user()->name ?? 'Pengguna';
    $pctLengkap = $summary['total_mk'] > 0 ? round($summary['mk_lengkap'] / $summary['total_mk'] * 100) : 0;

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

{{-- ── GREETING ── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-7 pb-5 border-b border-gray-100">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Selamat {{ $salam }}, {{ $namaDepan }}! 👋</h1>
        <p class="text-base text-gray-800 mt-1">{{ $quoteHari }}</p>
        <p class="text-base text-gray-400 mt-1">
            @if($summary['total_mk'] == 0)
                Belum ada mata kuliah yang terdaftar di program studi ini.
            @elseif($pctLengkap == 100)
                Semua mata kuliah sudah lengkap. 🎉
            @else
                Kelengkapan kurikulum saat ini
                <span class="font-medium text-amber-600">{{ $pctLengkap }}%</span>
                — {{ $summary['mk_lengkap'] }} dari {{ $summary['total_mk'] }} mata kuliah sudah lengkap.
            @endif
        </p>
    </div>
    <div class="shrink-0 text-xs text-gray-400 sm:text-right">
        <p class="font-medium text-gray-500">{{ now()->format('d M Y') }}</p>
        <p>{{ now()->format('H:i') }} WITA</p>
    </div>
</div>

{{-- ── INFO PANEL ── --}}
<div x-data="{ show: !localStorage.getItem('admin_info_dismissed') }" x-show="show" x-cloak
     class="mb-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4 flex gap-3 items-start">
    <svg class="shrink-0 w-5 h-5 text-blue-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
    </svg>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-blue-800">Tentang Aplikasi Ini</p>
        <p class="text-sm text-blue-700 mt-0.5 leading-relaxed">
            Sistem Manajemen Kurikulum S1 Manajemen FEB UNM digunakan untuk memantau kelengkapan dokumen
            kurikulum seluruh mata kuliah aktif. Setiap mata kuliah memiliki <strong>5 komponen</strong> yang
            harus diisi oleh dosen: Matriks MK–CPL, CPMK, Sub-CPMK, CLO Tertinggi, dan Bobot Asesmen.
            Kaprodi dapat memantau progres pengisian dan mengelola penugasan dosen.
        </p>
    </div>
    <button @click="show = false; localStorage.setItem('admin_info_dismissed', '1')"
            class="shrink-0 text-blue-300 hover:text-blue-500 transition-colors" title="Tutup">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- ── STAT CARDS ── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-7">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Total MK</p>
        <p class="text-3xl font-bold text-gray-700 mt-1">{{ $summary['total_mk'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">MK Lengkap</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $summary['mk_lengkap'] }}</p>
        <div class="mt-2 bg-gray-100 rounded-full h-1.5">
            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ round($summary['mk_lengkap']/$tot*100) }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-0.5">{{ round($summary['mk_lengkap']/$tot*100) }}% selesai</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">MK Belum Selesai</p>
        <p class="text-3xl font-bold text-amber-500 mt-1">{{ $summary['mk_pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wider">Total Sub-CPMK</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $summary['total_sub'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $summary['total_asesmen'] }} asesmen terisi</p>
    </div>
</div>

{{-- ── PIPELINE ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-7">
    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Progress Pengisian (per MK)</h2>
    <div class="flex items-center gap-1">
        @php
        $steps = [
            ['label' => '1. Matriks',    'pct' => $summary['pct_matriks'],  'color' => 'bg-gray-500'],
            ['label' => '2. CPMK',       'pct' => $summary['pct_cpmk'],     'color' => 'bg-emerald-500'],
            ['label' => '3. Sub-CPMK',   'pct' => $summary['pct_subcpmk'],  'color' => 'bg-blue-500'],
            ['label' => '4. CLO',        'pct' => $summary['pct_clo'],      'color' => 'bg-purple-500'],
            ['label' => '5. Asesmen',    'pct' => $summary['pct_asesmen'],  'color' => 'bg-amber-500'],
        ];
        @endphp
        @foreach($steps as $i => $step)
        <div class="flex-1">
            <div class="flex justify-between mb-1">
                <span class="text-xs text-gray-500">{{ $step['label'] }}</span>
                <span class="text-xs font-semibold {{ $step['pct']==100 ? 'text-green-600' : 'text-gray-400' }}">{{ $step['pct'] }}%</span>
            </div>
            <div class="bg-gray-100 rounded-full h-2">
                <div class="{{ $step['color'] }} h-2 rounded-full" style="width: {{ $step['pct'] }}%"></div>
            </div>
        </div>
        @if($i < count($steps)-1)<div class="text-gray-300 text-base mx-1">›</div>@endif
        @endforeach
    </div>
</div>

{{-- ── BREAKDOWN PER SEMESTER ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-7">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress per Semester</h2>
        <a href="{{ route('kelengkapan') }}" class="text-xs text-gray-700 hover:underline">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($bySemester as $smt => $data)
        <div class="rounded-lg border border-gray-100 p-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600">Smt {{ $smt }}</span>
                <span class="text-xs font-bold {{ $data['pct']==100 ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $data['pct'] }}%
                </span>
            </div>
            <div class="bg-gray-100 rounded-full h-1.5 mb-1">
                <div class="{{ $data['pct']==100 ? 'bg-green-500' : 'bg-indigo-400' }} h-1.5 rounded-full"
                     style="width: {{ $data['pct'] }}%"></div>
            </div>
            <p class="text-xs text-gray-400">{{ $data['lengkap'] }}/{{ $data['total'] }} MK</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ── RINGKASAN ASESMEN ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-7">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ringkasan Asesmen per Mata Kuliah</h2>
        <a href="{{ route('kurikulum.asesmen.index') }}" class="text-xs text-gray-700 hover:underline">Input Asesmen →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 text-left">Mata Kuliah</th>
                    <th class="px-3 py-2.5 text-center w-12">Smt</th>
                    <th class="px-3 py-2.5 text-center w-32">Progress Asesmen</th>
                    <th class="px-3 py-2.5 text-center w-20">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mkAsesmen as $mk)
                @php
                    $pct = $mk->sub_cpmks_count > 0 ? round($mk->sub_asesmen_count / $mk->sub_cpmks_count * 100) : 0;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <span class="font-mono text-gray-700 mr-1.5">{{ $mk->kode_rps }}</span>
                        <span class="text-gray-700">{{ $mk->nama }}</span>
                    </td>
                    <td class="px-3 py-2 text-center text-gray-500">{{ $mk->semester }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $pct == 100 ? 'bg-green-500' : ($pct > 0 ? 'bg-amber-400' : 'bg-gray-200') }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-gray-400 w-14 text-right shrink-0">{{ $mk->sub_asesmen_count }}/{{ $mk->sub_cpmks_count }}</span>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-center">
                        @if($pct == 100)
                            <span class="inline-block px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">Lengkap</span>
                        @elseif($pct > 0)
                            <span class="inline-block px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">{{ $pct }}%</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full bg-gray-100 text-gray-400">Kosong</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 italic">Belum ada Sub-CPMK yang terdaftar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- ── TABEL MK BELUM SELESAI ── --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700 text-sm">Mata Kuliah Belum Selesai</h2>
            <a href="{{ route('admin.penugasan.index') }}" class="text-xs text-gray-700 hover:underline">Kelola Penugasan →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5 text-left">MK</th>
                        <th class="px-3 py-2.5 text-center w-24">Langkah</th>
                        <th class="px-4 py-2.5 text-left">Dosen Ditugaskan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($mkPending->flatten() as $mk)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <p class="font-mono text-gray-700">{{ $mk->kode_rps }}</p>
                            <p class="text-gray-600 truncate max-w-xs">{{ $mk->nama }}</p>
                            <p class="text-gray-400">Smt {{ $mk->semester }}</p>
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full font-semibold
                                {{ $mk->score == 0 ? 'bg-gray-100 text-gray-400' : 'bg-amber-100 text-amber-700' }}">
                                {{ $mk->score }}/5
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            @forelse($mk->dosens as $d)
                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full mr-1 mb-1">{{ $d->name }}</span>
                            @empty
                            <span class="text-gray-300 italic">—</span>
                            @endforelse
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-green-600 font-medium">
                        🎉 Semua mata kuliah sudah lengkap!
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── RINGKASAN PER DOSEN ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-700 text-sm">Ringkasan per Dosen</h2>
            <span class="text-xs text-gray-400">{{ $dosens->count() }} dosen</span>
        </div>
        <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
            @forelse($dosens as $d)
            <div class="px-5 py-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $d->name }}</p>
                        @if($d->nip)<p class="text-xs text-gray-400">{{ $d->nip }}</p>@endif
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs text-gray-500">{{ $d->total_mk }} MK</p>
                        <p class="text-xs {{ $d->mk_pending > 0 ? 'text-amber-600' : 'text-green-600' }} font-medium">
                            {{ $d->mk_pending > 0 ? $d->mk_pending.' belum selesai' : 'Semua selesai' }}
                        </p>
                    </div>
                </div>
                @if($d->total_mk > 0)
                <div class="mt-2 bg-gray-100 rounded-full h-1.5">
                    <div class="bg-green-500 h-1.5 rounded-full"
                         style="width: {{ $d->total_mk > 0 ? round($d->mk_lengkap/$d->total_mk*100) : 0 }}%"></div>
                </div>
                @else
                <p class="text-xs text-gray-300 mt-1 italic">Belum ada penugasan</p>
                @endif
            </div>
            @empty
            <x-empty-state icon="users" title="Belum ada dosen terdaftar"/>
            @endforelse
        </div>
    </div>
</div>

@endsection

@section('navbar_version', 'Sistem Informasi Kurikulum versi 1.2.7')
