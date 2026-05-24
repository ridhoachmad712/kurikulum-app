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
    if (!confirm(`Hapus ${kode}?`)) return;
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF },
    });
    if ((await res.json()).ok) {
        document.getElementById('sc-card-' + id)?.remove();
    }
}

async function saveClo(id, cloId) {
    const res = await fetch(`/kurikulum/api/sub-cpmk/${id}/clo`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ clo_id: cloId }),
    });
    const json = await res.json();
    if (json.ok) {
        const badge = document.getElementById('clo-badge-' + id);
        if (badge) badge.textContent = json.kode_clo_tertinggi ?? '–';
    }
}

function showMsg(el, text, cls) {
    el.textContent = text;
    el.className = 'text-xs mt-1 ' + cls;
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 3000);
}
</script>

@endsection
