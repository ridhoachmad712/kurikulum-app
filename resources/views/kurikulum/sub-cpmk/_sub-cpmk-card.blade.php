<div id="sc-card-{{ $sc->id }}" class="bg-white border border-gray-200 rounded-xl p-4">
    <div class="flex items-start gap-3">
        <div class="shrink-0 w-8 h-8 bg-teal-100 text-teal-700 rounded-full flex items-center justify-center text-xs font-bold">
            {{ $sc->urutan }}
        </div>

        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wide">{{ $sc->kode }}</span>

                {{-- CLO Tertinggi badge --}}
                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                    {{ $sc->kode_clo_tertinggi ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-400' }}">
                    CLO: <span id="clo-badge-{{ $sc->id }}">{{ $sc->kode_clo_tertinggi ?? '–' }}</span>
                </span>

                <button onclick="startEdit({{ $sc->id }})" class="text-gray-400 hover:text-indigo-600 text-xs ml-2">✏ Edit</button>
                <button onclick="deleteSubCpmk({{ $sc->id }}, '{{ $sc->kode }}')" class="text-gray-300 hover:text-red-500 text-xs ml-1">✕ Hapus</button>
            </div>

            {{-- Deskripsi view --}}
            <p id="desc-view-{{ $sc->id }}" class="text-sm text-gray-700 leading-relaxed">{{ $sc->deskripsi }}</p>

            {{-- Deskripsi edit --}}
            <div id="desc-edit-{{ $sc->id }}" class="hidden">
                <textarea rows="2"
                    class="w-full border border-indigo-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-400 resize-none"
                    id="desc-input-{{ $sc->id }}">{{ $sc->deskripsi }}</textarea>
                <div class="flex gap-2 mt-1">
                    <button onclick="saveEdit({{ $sc->id }})" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700">Simpan</button>
                    <button onclick="cancelEdit({{ $sc->id }})" class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg hover:bg-gray-200">Batal</button>
                </div>
            </div>

            {{-- CLO Taxonomy selector --}}
            <div class="mt-3">
                <p class="text-xs text-gray-400 mb-1.5">CLO Tertinggi (Taksonomi Bloom):</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($clos as $domain => $items)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">{{ $domain }}</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($items as $clo)
                            @php $selected = $sc->kode_clo_tertinggi === $clo->kode; @endphp
                            <button
                                onclick="saveClo({{ $sc->id }}, {{ $clo->id }})"
                                title="{{ $clo->deskripsi }}"
                                class="px-2 py-0.5 rounded text-xs font-mono font-semibold border transition-colors
                                    {{ $selected
                                        ? 'bg-purple-600 text-white border-purple-600'
                                        : 'bg-white text-gray-500 border-gray-300 hover:border-purple-400 hover:text-purple-600' }}">
                                {{ $clo->kode }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
