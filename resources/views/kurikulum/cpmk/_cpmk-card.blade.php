<div id="cpmk-card-{{ $cpmk->id }}" class="cpmk-card bg-white border border-gray-200 rounded-xl p-4">
    <div class="flex items-start gap-3">
        {{-- Nomor urut --}}
        <div class="shrink-0 w-8 h-8 bg-gray-100 text-gray-800 rounded-full flex items-center justify-center text-xs font-bold">
            {{ $cpmk->urutan }}
        </div>

        <div class="flex-1 min-w-0">
            {{-- Header CPMK --}}
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">{{ $cpmk->kode }}</span>
                <button onclick="startEdit({{ $cpmk->id }})"
                    class="text-gray-400 hover:text-gray-700 text-xs ml-2">✏ Edit</button>
                <button onclick="deleteCpmk({{ $cpmk->id }}, '{{ $cpmk->kode }}')"
                    class="text-gray-300 hover:text-red-500 text-xs ml-1">✕ Hapus</button>
            </div>

            {{-- Deskripsi (view mode) --}}
            <p id="desc-view-{{ $cpmk->id }}" class="text-sm text-gray-700 leading-relaxed">
                {{ $cpmk->deskripsi }}
            </p>

            {{-- Deskripsi (edit mode) --}}
            <div id="desc-edit-{{ $cpmk->id }}" class="hidden">
                <textarea rows="2"
                    class="w-full border border-indigo-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-gray-400 resize-none"
                    id="desc-input-{{ $cpmk->id }}">{{ $cpmk->deskripsi }}</textarea>
                <div class="flex gap-2 mt-1">
                    <button onclick="saveEdit({{ $cpmk->id }})"
                        class="px-3 py-1 bg-gray-700 text-white text-xs rounded-lg hover:bg-gray-700">Simpan</button>
                    <button onclick="cancelEdit({{ $cpmk->id }})"
                        class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg hover:bg-gray-200">Batal</button>
                </div>
            </div>

            {{-- CPL Badges --}}
            <div class="flex flex-wrap gap-1.5 mt-3">
                <span class="text-xs text-gray-500 font-medium self-center">CPL yang didukung <span class="font-normal text-gray-400">(klik untuk pilih):</span></span>
                @foreach($cpls as $cpl)
                @php $checked = $cpmkCplMap[$cpmk->id][$cpl->id]['has_relation'] ?? false; @endphp
                <button type="button"
                    id="cpl-label-{{ $cpmk->id }}-{{ $cpl->id }}"
                    data-checked="{{ $checked ? '1' : '0' }}"
                    onclick="toggleCpl(this, {{ $cpmk->id }}, {{ $cpl->id }})"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-xs transition-colors
                           {{ $checked ? 'bg-indigo-100 border-indigo-400 text-indigo-800 font-semibold' : 'bg-gray-50 border-gray-200 text-gray-400 hover:border-gray-300' }}">
                    {{ $cpl->kode }}
                </button>
                @endforeach
            </div>

            {{-- Link ke Sub-CPMK --}}
            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                <span class="text-xs text-gray-400">
                    {{ $cpmk->subCpmks->count() }} Sub-CPMK
                </span>
                <a href="{{ route('kurikulum.sub-cpmk.show', $cpmk) }}"
                   class="inline-flex items-center gap-1 text-xs text-gray-600 hover:text-gray-900 font-medium hover:underline">
                    Kelola Sub-CPMK
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function startEdit(id) {
    document.getElementById('desc-view-'+id).classList.add('hidden');
    document.getElementById('desc-edit-'+id).classList.remove('hidden');
}
function cancelEdit(id) {
    document.getElementById('desc-view-'+id).classList.remove('hidden');
    document.getElementById('desc-edit-'+id).classList.add('hidden');
}
async function saveEdit(id) {
    const val = document.getElementById('desc-input-'+id).value.trim();
    if (!val) return;
    await fetch(`/kurikulum/api/cpmk/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ deskripsi: val }),
    });
    document.getElementById('desc-view-'+id).textContent = val;
    cancelEdit(id);
}
</script>
