@extends('layouts.app')
@section('title','Sub-CPMK: '.$cpmk->kode)
@section('content')

{{-- Breadcrumb & info CPMK --}}
<div class="mb-5">
    <x-page-header
        title="Sub-CPMK — {{ $cpmk->kode }}"
        subtitle="{{ $cpmk->mataKuliah->kode_rps }} · {{ $cpmk->mataKuliah->nama }}"
        back="{{ route('kurikulum.sub-cpmk.index') }}"
        backLabel="Sub-CPMK"/>
    {{-- Quick nav ke modul lain --}}
    <div class="mt-3 flex items-center gap-3 text-xs">
        <a href="{{ route('kurikulum.cpmk.show', $cpmk->mataKuliah) }}"
           class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 hover:underline">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            CPMK
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('kurikulum.asesmen.show', $cpmk->mataKuliah) }}"
           class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 hover:underline">
            Bobot Asesmen
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="mt-3 bg-gray-50 border border-gray-200 rounded-xl px-5 py-4">
        <div class="flex items-start gap-3">
            <div class="shrink-0 w-10 h-10 bg-gray-700 text-white rounded-full flex items-center justify-center text-sm font-bold">
                {{ $cpmk->urutan }}
            </div>
            <div>
                <p class="font-bold text-gray-800 text-sm">{{ $cpmk->kode }}</p>
                <p class="text-gray-700 text-sm mt-0.5">{{ $cpmk->deskripsi }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $cpmk->mataKuliah->kode_rps }} · {{ $cpmk->mataKuliah->nama }}</p>
            </div>
        </div>
        @if($cpmk->cpmkCpls->isNotEmpty())
        <div class="flex flex-wrap gap-1.5 mt-3 pl-13">
            <span class="text-xs text-gray-400">CPL yang didukung:</span>
            @foreach($cpmk->cpmkCpls->where('has_relation',true) as $cc)
            <span class="px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ $cc->cpl->kode }}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Panduan pengisian Sub-CPMK --}}
<div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 text-xs text-blue-700">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
    </svg>
    <div class="space-y-1">
        <p class="font-semibold text-blue-800">Panduan Pengisian Sub-CPMK</p>
        <p>Sub-CPMK adalah penjabaran kemampuan spesifik yang perlu dikuasai mahasiswa untuk memenuhi <strong>{{ $cpmk->kode }}</strong> di atas. Tuliskan setiap tahapan kemampuan sebagai satu Sub-CPMK dengan kata kerja operasional yang terukur.</p>
        <p>Untuk setiap Sub-CPMK, tentukan <strong>CLO Tertinggi per Ranah</strong> — yaitu level taksonomi tertinggi yang ditargetkan pada ranah <em>Kognitif</em>, <em>Afektif</em>, dan/atau <em>Psikomotorik</em>. Pilih maksimal 1 per ranah. Klik tombol CLO untuk menyimpan, klik lagi untuk membatalkan.</p>
        <p>Setelah semua Sub-CPMK terisi, lanjutkan ke menu <strong>Bobot Asesmen</strong> untuk mendistribusikan bobot penilaian per Sub-CPMK.</p>
    </div>
</div>

{{-- Form tambah Sub-CPMK --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Tambah Sub-CPMK Baru</h3>
    <div class="flex gap-3">
        <textarea id="new-deskripsi" rows="2" placeholder="Tuliskan deskripsi Sub-CPMK..."
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 resize-none"></textarea>
        <button onclick="addSubCpmk()"
            class="shrink-0 px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-700 self-start">
            + Tambah
        </button>
    </div>
    <p id="add-msg" class="text-xs mt-1 hidden"></p>
</div>

{{-- Daftar Sub-CPMK --}}
<div id="sub-cpmk-list" class="space-y-3">
    @forelse($subCpmks as $sc)
        @include('kurikulum.sub-cpmk._sub-cpmk-card', ['sc' => $sc, 'clos' => $clos])
    @empty
    <div id="empty-msg" class="text-center py-10 text-gray-400 text-sm">Belum ada Sub-CPMK. Tambahkan di atas.</div>
    @endforelse
</div>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content
    || '{{ csrf_token() }}';
const CPMK_ID = {{ $cpmk->id }};

async function addSubCpmk() {
    const desc = document.getElementById('new-deskripsi').value.trim();
    const msg  = document.getElementById('add-msg');
    if (!desc) { showMsg(msg, 'Deskripsi tidak boleh kosong.', 'text-red-500'); return; }

    const res = await fetch('{{ route("kurikulum.sub-cpmk.store", $cpmk) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ deskripsi: desc }),
    });
    const json = await res.json();
    if (!json.ok) { showMsg(msg, 'Gagal menyimpan.', 'text-red-500'); return; }

    document.getElementById('new-deskripsi').value = '';
    document.getElementById('empty-msg')?.remove();
    showMsg(msg, '✓ Sub-CPMK ditambahkan. Memuat ulang...', 'text-green-600');
    setTimeout(() => location.reload(), 700);
}

async function saveEdit(id) {
    const val = document.getElementById('desc-input-' + id).value.trim();
    if (!val) return;
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ deskripsi: val }),
    });
    if ((await res.json()).ok) {
        document.getElementById('desc-view-' + id).textContent = val;
        cancelEdit(id);
    }
}

function startEdit(id) {
    document.getElementById('desc-view-' + id).classList.add('hidden');
    document.getElementById('desc-edit-' + id).classList.remove('hidden');
    document.getElementById('desc-input-' + id).focus();
}
function cancelEdit(id) {
    document.getElementById('desc-view-' + id).classList.remove('hidden');
    document.getElementById('desc-edit-' + id).classList.add('hidden');
}

async function deleteSubCpmk(id, kode) {
    if (!confirm(`Hapus ${kode}?\n\nData berikut akan ikut terhapus permanen:\n• CLO yang dipilih untuk ${kode}\n• Bobot asesmen ${kode}\n\nLanjutkan?`)) return;
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });
    if ((await res.json()).ok) {
        document.getElementById('sc-card-' + id)?.remove();
    }
}

async function saveClo(scId, cloId) {
    // Tampilkan loading sementara pada tombol yang diklik
    const clickedBtn = document.getElementById(`clo-btn-${scId}-${cloId}`);
    if (clickedBtn) {
        clickedBtn.disabled = true;
        clickedBtn.style.opacity = '0.6';
    }

    let json;
    try {
        const res = await fetch(`/kurikulum/api/sub-cpmk/${scId}/clo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ clo_id: cloId }),
        });
        json = await res.json();
    } catch (e) {
        showCloStatus(scId, false);
        if (clickedBtn) { clickedBtn.disabled = false; clickedBtn.style.opacity = ''; }
        return;
    }

    if (clickedBtn) { clickedBtn.disabled = false; clickedBtn.style.opacity = ''; }

    if (!json.ok) {
        showCloStatus(scId, false);
        return;
    }

    // Update badge text
    const badge = document.getElementById('clo-badge-' + scId);
    if (badge) badge.textContent = json.kode_clo_badge ?? '–';

    // Update badge wrapper color
    const wrap = document.getElementById('clo-badge-wrap-' + scId);
    if (wrap) {
        if (json.kode_clo_badge) {
            wrap.classList.remove('bg-gray-100', 'text-gray-400');
            wrap.classList.add('bg-purple-100', 'text-purple-700');
        } else {
            wrap.classList.remove('bg-purple-100', 'text-purple-700');
            wrap.classList.add('bg-gray-100', 'text-gray-400');
        }
    }

    // Update semua tombol CLO di kartu ini
    const selectedIds = json.selected_ids || [];
    document.querySelectorAll(`[data-sc-id="${scId}"]`).forEach(btn => {
        const btnCloId = parseInt(btn.dataset.cloId);
        const isSelected = selectedIds.includes(btnCloId);
        if (isSelected) {
            btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-300',
                'hover:border-purple-400', 'hover:text-purple-600');
            btn.classList.add('bg-purple-600', 'text-white', 'border-purple-600');
        } else {
            btn.classList.remove('bg-purple-600', 'text-white', 'border-purple-600');
            btn.classList.add('bg-white', 'text-gray-500', 'border-gray-300',
                'hover:border-purple-400', 'hover:text-purple-600');
        }
    });

    showCloStatus(scId, true);
}

function showCloStatus(scId, success) {
    const okEl  = document.getElementById('clo-save-status-' + scId);
    const errEl = document.getElementById('clo-save-error-' + scId);
    if (!okEl || !errEl) return;

    okEl.classList.add('hidden');
    errEl.classList.add('hidden');

    const el = success ? okEl : errEl;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 2500);
}

function showMsg(el, text, cls) {
    el.textContent = text;
    el.className = 'text-xs mt-1 ' + cls;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}
</script>

@endsection
