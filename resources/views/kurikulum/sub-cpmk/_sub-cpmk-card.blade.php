<div id="sc-card-{{ $sc->id }}" class="bg-white border border-gray-200 rounded-xl p-4">
    <div class="flex items-start gap-3">

        {{-- Nomor urut --}}
        <div class="shrink-0 w-8 h-8 bg-teal-100 text-teal-700 rounded-full flex items-center justify-center text-xs font-bold">
            {{ $sc->urutan }}
        </div>

        <div class="flex-1 min-w-0">

            {{-- ── Header ── --}}
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wide">{{ $sc->kode }}</span>

                <span id="clo-badge-wrap-{{ $sc->id }}"
                      class="text-xs px-2 py-0.5 rounded-full font-medium
                             {{ $sc->kode_clo_tertinggi ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-400' }}">
                    CLO: <span id="clo-badge-{{ $sc->id }}">{{ $sc->kode_clo_tertinggi ?? '–' }}</span>
                </span>

                <button onclick="startEdit({{ $sc->id }})"
                        class="text-gray-400 hover:text-gray-700 text-xs ml-2">✏ Edit</button>
                <button onclick="deleteSubCpmk({{ $sc->id }}, '{{ $sc->kode }}')"
                        class="text-gray-300 hover:text-red-500 text-xs ml-1">✕ Hapus</button>
            </div>

            {{-- ── Deskripsi view ── --}}
            <p id="desc-view-{{ $sc->id }}" class="text-sm text-gray-700 leading-relaxed">{{ $sc->deskripsi }}</p>

            {{-- ── Deskripsi edit ── --}}
            <div id="desc-edit-{{ $sc->id }}" class="hidden">
                <textarea rows="2"
                    class="w-full border border-indigo-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-gray-400 resize-none"
                    id="desc-input-{{ $sc->id }}">{{ $sc->deskripsi }}</textarea>
                <div class="flex gap-2 mt-1">
                    <button onclick="saveEdit({{ $sc->id }})" class="btn-primary btn-sm">Simpan</button>
                    <button onclick="cancelEdit({{ $sc->id }})" class="btn-secondary btn-sm">Batal</button>
                </div>
            </div>

            {{-- ── CLO Taxonomy selector ── --}}
            @php
            $selectedCloIds = $sc->subCpmkClos->pluck('clo_id')->toArray();
            $domainClr = [
                'Kognitif'     => [
                    'label' => 'text-blue-600',
                    'sel'   => 'bg-blue-600 text-white border-blue-600',
                    'unsel' => 'bg-white text-gray-500 border-gray-300 hover:border-blue-400 hover:text-blue-600',
                ],
                'Afektif'      => [
                    'label' => 'text-green-600',
                    'sel'   => 'bg-green-600 text-white border-green-600',
                    'unsel' => 'bg-white text-gray-500 border-gray-300 hover:border-green-500 hover:text-green-600',
                ],
                'Psikomotorik' => [
                    'label' => 'text-teal-600',
                    'sel'   => 'bg-teal-600 text-white border-teal-600',
                    'unsel' => 'bg-white text-gray-500 border-gray-300 hover:border-teal-400 hover:text-teal-600',
                ],
            ];
            $defaultClr = [
                'label' => 'text-gray-400',
                'sel'   => 'bg-purple-600 text-white border-purple-600',
                'unsel' => 'bg-white text-gray-500 border-gray-300 hover:border-purple-400 hover:text-purple-600',
            ];
            @endphp
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <p class="text-xs text-gray-400">
                        CLO Tertinggi per Ranah
                        <span class="text-gray-300">(klik untuk simpan — maks 1 per ranah)</span>
                    </p>
                    <span id="clo-save-status-{{ $sc->id }}"
                          class="hidden text-xs font-medium text-green-600 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">
                        ✓ Tersimpan
                    </span>
                    <span id="clo-save-error-{{ $sc->id }}"
                          class="hidden text-xs font-medium text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full">
                        ✕ Gagal menyimpan
                    </span>
                </div>
                <div class="flex flex-wrap gap-4">
                    @foreach($clos as $domain => $items)
                    @php $clr = $domainClr[$domain] ?? $defaultClr; @endphp
                    <div>
                        <p class="text-xs font-medium {{ $clr['label'] }} mb-1">{{ $domain }}</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($items as $clo)
                            @php $selected = in_array($clo->id, $selectedCloIds); @endphp
                            <button
                                id="clo-btn-{{ $sc->id }}-{{ $clo->id }}"
                                data-sc-id="{{ $sc->id }}"
                                data-clo-id="{{ $clo->id }}"
                                data-domain="{{ $domain }}"
                                onclick="saveClo({{ $sc->id }}, {{ $clo->id }})"
                                title="{{ $clo->deskripsi }}"
                                class="px-2 py-0.5 rounded text-xs font-mono font-semibold border transition-colors
                                    {{ $selected ? $clr['sel'] : $clr['unsel'] }}">
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
