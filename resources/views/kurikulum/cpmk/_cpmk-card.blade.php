<div id="cpmk-card-{{ $cpmk->id }}" class="cpmk-card bg-white border border-gray-200 rounded-xl p-4">
    <div class="flex items-start gap-3">
        {{-- Nomor urut --}}
        <div class="shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold">
            {{ $cpmk->urutan }}
        </div>

        <div class="flex-1 min-w-0">
            {{-- Header CPMK --}}
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wide">{{ $cpmk->kode }}</span>
                <button onclick="startEdit({{ $cpmk->id }})"
                    class="text-gray-400 hover:text-indigo-600 text-xs ml-2">✏ Edit</button>
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
                    class="w-full border border-indigo-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-400 resize-none"
                    id="desc-input-{{ $cpmk->id }}">{{ $cpmk->deskripsi }}</textarea>
                <div class="flex gap-2 mt-1">
                    <button onclick="saveEdit({{ $cpmk->id }})"
                        class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700">Simpan</button>
                    <button onclick="cancelEdit({{ $cpmk->id }})"
                        class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg hover:bg-gray-200">Batal</button>
                </div>
            </div>

            {{-- CPL Badges --}}
            <div class="flex flex-wrap gap-1.5 mt-3">
                <span class="text-xs text-gray-400 self-center">CPL yang didukung:</span>
                @foreach($cpls as $cpl)
                @php $checked = $cpmkCplMap[$cpmk->id][$cpl->id]['has_relation'] ?? false; @endphp
                <label class="inline-flex items-center gap-1 cursor-pointer px-2 py-0.5 rounded-full border text-xs
                              {{ $checked ? 'bg-indigo-100 border-indigo-300 text-indigo-700' : 'bg-gray-50 border-gray-200 text-gray-400' }}">
                    <input type="checkbox"
                        class="sr-only"
                        {{ $checked ? 'checked' : '' }}
                        onchange="toggleCpl(this, {{ $cpmk->id }}, {{ $cpl->id }})">
                    {{ $cpl->kode }}
                </label>
                @endforeach
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
