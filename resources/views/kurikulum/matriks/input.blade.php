@extends('layouts.app')
@section('title', auth()->user()?->hasRole('dosen') ? 'Level CPL Mata Kuliah' : 'Matriks Keterkaitan MK & CPL')
@section('content')

<x-page-header
    :title="auth()->user()?->hasRole('dosen') ? 'Level CPL Mata Kuliah' : 'Input Level Keterkaitan'"
    :subtitle="auth()->user()?->hasRole('dosen') ? 'Isi tingkat keterkaitan setiap mata kuliah Anda terhadap CPL (Tinggi / Sedang / Rendah)' : 'Tentukan level keterkaitan setiap MK terhadap CPL'"/>

@hasanyrole('admin|kaprodi')
@include('kurikulum.matriks._tabs')
@endhasanyrole

<x-dosen-stepper :step="1" />

{{-- Info scope dosen --}}
@hasrole('dosen')
<div class="flex items-start gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4 text-xs text-gray-600">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span class="space-y-0.5 flex flex-col">
        <span>Isi level keterkaitan setiap mata kuliah Anda terhadap CPL (Tinggi / Sedang / Rendah).</span>
        <span><strong>Hasil isian ini akan otomatis mengisi Tabel 1–5</strong> pada Tabel Kurikulum.</span>
        <span>Hanya mata kuliah yang ditugaskan kepada Anda yang ditampilkan.</span>
    </span>
</div>
@endhasrole

{{-- Legend & save status --}}
<div class="flex items-center flex-wrap gap-2.5 mb-4">
    <span class="text-xs font-medium text-gray-500">Level:</span>
    <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 text-[11px] font-bold ring-1 ring-inset ring-red-200">T · Tinggi</span>
    <span class="px-2.5 py-0.5 rounded-full bg-yellow-50 text-yellow-600 text-[11px] font-bold ring-1 ring-inset ring-yellow-200">S · Sedang</span>
    <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-600 text-[11px] font-bold ring-1 ring-inset ring-green-200">R · Rendah</span>
    <span class="text-xs text-gray-300 mx-1">|</span>
    <span class="text-[11px] text-gray-400">Klik sel untuk mengubah level</span>
    <div class="ml-auto flex items-center gap-1.5">
        <span id="save-indicator" class="hidden text-[11px] text-gray-400 flex items-center gap-1">
            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            Menyimpan...
        </span>
        <span id="save-ok" class="hidden text-[11px] text-green-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Tersimpan
        </span>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[70vh]">
        <table class="text-xs border-collapse w-full">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-gray-900 px-3 py-3 w-8 text-right font-normal text-gray-500">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-gray-900 px-4 py-3 text-left min-w-[260px] font-semibold text-gray-100">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-gray-900 px-2 py-3 text-center w-[108px]" title="{{ $cpl->deskripsi ?? $cpl->kode }}">
                        <span class="text-[11px] font-bold tracking-wide text-gray-200">{{ $cpl->kode }}</span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                <tr class="border-b border-gray-50 hover:bg-slate-50/70 transition-colors duration-100 group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-slate-50/70 px-3 py-3 text-[11px] text-gray-300 text-right transition-colors">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-slate-50/70 px-4 py-3 border-r border-gray-100 transition-colors">
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-gray-800 text-[13px] leading-tight">{{ $mk->nama }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono text-[10px] text-gray-400">{{ $mk->kode_rps }}</span>
                                <span class="text-gray-200">·</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-gray-100 text-gray-400 text-[10px] font-medium">Semester {{ $mk->semester }}</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-gray-100 text-gray-400 text-[10px] font-medium">{{ $mk->sks }} SKS</span>
                            </div>
                        </div>
                    </td>
                    @foreach($cpls as $cpl)
                    @php $lv = $matriksInput[$mk->id][$cpl->id] ?? ''; @endphp
                    <td class="px-1.5 py-2.5 text-center border-r border-gray-50">
                        <button
                            type="button"
                            data-mk="{{ $mk->id }}"
                            data-cpl="{{ $cpl->id }}"
                            data-level="{{ $lv }}"
                            onclick="openDropdown(event, this)"
                            title="{{ $cpl->kode }} — klik untuk mengubah level"
                            class="cell-btn inline-flex items-center justify-center gap-1 w-full px-2 py-1.5 rounded-lg text-[11px] font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-300
                                   {{ $lv === 'Tinggi' ? 'bg-red-100 text-red-600 ring-1 ring-red-200 hover:bg-red-200' :
                                     ($lv === 'Sedang' ? 'bg-yellow-100 text-yellow-600 ring-1 ring-yellow-200 hover:bg-yellow-200' :
                                     ($lv === 'Rendah' ? 'bg-green-100 text-green-600 ring-1 ring-green-200 hover:bg-green-200' :
                                     'text-gray-300 hover:bg-gray-100 hover:text-gray-400')) }}">
                            <span class="level-text">{{ $lv ?: '—' }}</span>
                            <svg class="w-2.5 h-2.5 shrink-0 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Shared dropdown panel --}}
<div id="cell-dropdown"
     class="hidden fixed z-[200] bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 w-36 overflow-hidden">
    @foreach(['' => ['—', 'text-gray-400', 'hover:bg-gray-50', ''], 'Tinggi' => ['Tinggi', 'text-red-600', 'hover:bg-red-50', 'bg-red-400'], 'Sedang' => ['Sedang', 'text-yellow-600', 'hover:bg-yellow-50', 'bg-yellow-400'], 'Rendah' => ['Rendah', 'text-green-600', 'hover:bg-green-50', 'bg-green-400']] as $val => [$label, $textCls, $hoverCls, $dotCls])
    <button type="button"
            data-val="{{ $val }}"
            onclick="selectLevel('{{ $val }}')"
            class="dropdown-opt w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium {{ $textCls }} {{ $hoverCls }} transition-colors">
        <span class="w-2 h-2 rounded-full shrink-0 {{ $dotCls ?: 'bg-gray-200' }}"></span>
        <span class="flex-1 text-left">{{ $label }}</span>
        <svg class="check-icon w-3 h-3 invisible shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </button>
    @endforeach
</div>

<script>
const CSRF     = '{{ csrf_token() }}';
const SAVE_URL = '{{ route("kurikulum.api.matriks.save-cell") }}';

const btnColors = {
    'Tinggi': ['bg-red-100',    'text-red-600',    'ring-1', 'ring-red-200',    'hover:bg-red-200'],
    'Sedang': ['bg-yellow-100', 'text-yellow-600', 'ring-1', 'ring-yellow-200', 'hover:bg-yellow-200'],
    'Rendah': ['bg-green-100',  'text-green-600',  'ring-1', 'ring-green-200',  'hover:bg-green-200'],
    '':       ['text-gray-300', 'hover:bg-gray-100', 'hover:text-gray-400'],
};
const allColorClasses = Object.values(btnColors).flat();

let activeBtn  = null;
let saveTimer;
const dropdown  = document.getElementById('cell-dropdown');
const indicator = document.getElementById('save-indicator');
const saveOk    = document.getElementById('save-ok');

function openDropdown(e, btn) {
    e.stopPropagation();
    if (activeBtn === btn) { closeDropdown(); return; }

    activeBtn = btn;
    const current = btn.dataset.level;

    // Update checkmarks
    dropdown.querySelectorAll('.dropdown-opt').forEach(opt => {
        opt.querySelector('.check-icon').classList.toggle('invisible', opt.dataset.val !== current);
    });

    // Position: below the button, aligned left
    const rect = btn.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    if (spaceBelow >= 140) {
        dropdown.style.top  = (rect.bottom + 4) + 'px';
    } else {
        dropdown.style.top  = (rect.top - 4 - dropdown.offsetHeight) + 'px';
    }
    dropdown.style.left = rect.left + 'px';
    dropdown.classList.remove('hidden');
}

function closeDropdown() {
    dropdown.classList.add('hidden');
    activeBtn = null;
}

function selectLevel(val) {
    if (!activeBtn) return;
    const btn = activeBtn;
    closeDropdown();

    btn.dataset.level = val;
    btn.querySelector('.level-text').textContent = val || '—';
    allColorClasses.forEach(c => btn.classList.remove(c));
    btnColors[val].forEach(c => btn.classList.add(c));

    indicator.classList.remove('hidden');
    saveOk.classList.add('hidden');

    clearTimeout(saveTimer);
    saveTimer = setTimeout(async () => {
        try {
            const res = await fetch(SAVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    mata_kuliah_id: btn.dataset.mk,
                    cpl_id:         btn.dataset.cpl,
                    level:          val || null,
                }),
            });
            if (res.ok) {
                indicator.classList.add('hidden');
                saveOk.classList.remove('hidden');
                setTimeout(() => saveOk.classList.add('hidden'), 2000);
            }
        } catch (e) { console.error(e); }
    }, 400);
}

// Close on outside click
document.addEventListener('click', e => {
    if (!dropdown.contains(e.target) && !e.target.closest('.cell-btn')) closeDropdown();
});
</script>
@endsection

@section('help')
<div class="space-y-4">
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Apa itu Level Keterkaitan?</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Level keterkaitan menunjukkan seberapa besar sebuah mata kuliah berkontribusi terhadap pencapaian suatu CPL.</p>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-2">Pilihan Level</h3>
        <div class="space-y-2">
            <div class="flex items-start gap-2.5">
                <span class="mt-0.5 px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[11px] font-bold shrink-0">Tinggi</span>
                <p class="text-xs text-gray-500">MK ini secara dominan mendukung CPL tersebut. Biasanya MK inti atau prasyarat langsung.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="mt-0.5 px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 text-[11px] font-bold shrink-0">Sedang</span>
                <p class="text-xs text-gray-500">MK ini mendukung CPL secara parsial, sebagai bagian dari topik yang lebih luas.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="mt-0.5 px-2 py-0.5 rounded-full bg-green-100 text-green-600 text-[11px] font-bold shrink-0">Rendah</span>
                <p class="text-xs text-gray-500">MK ini menyinggung CPL secara ringan, bukan fokus utama.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="mt-0.5 px-2 py-0.5 rounded-full bg-gray-100 text-gray-400 text-[11px] font-bold shrink-0">—</span>
                <p class="text-xs text-gray-500">Tidak ada keterkaitan antara MK dan CPL ini.</p>
            </div>
        </div>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-1.5">Cara Mengisi</h3>
        <ol class="list-decimal list-inside space-y-1 text-xs text-gray-500">
            <li>Klik tombol pada baris MK dan kolom CPL yang ingin diisi.</li>
            <li>Pilih level dari dropdown yang muncul.</li>
            <li>Data tersimpan otomatis, tidak perlu klik Simpan.</li>
        </ol>
    </div>
    <div class="bg-blue-50 rounded-xl px-4 py-3">
        <p class="text-xs text-blue-700 font-medium">💡 Tips</p>
        <p class="text-xs text-blue-600 mt-1 leading-relaxed">Isian di halaman ini otomatis mengisi <strong>Tabel 1–5</strong> pada Tabel Kurikulum. Pastikan semua MK sudah terisi sebelum batas waktu yang ditentukan.</p>
    </div>
</div>
@endsection
