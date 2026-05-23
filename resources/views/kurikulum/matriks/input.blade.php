@extends('layouts.app')
@section('title','Matriks Keterkaitan MK & CPL')
@section('content')

@include('kurikulum.matriks._tabs')

{{-- Legend --}}
<div class="flex items-center gap-4 mb-4 text-xs">
    <span class="font-medium text-gray-600">Level keterkaitan:</span>
    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 font-medium">Tinggi</span>
    <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 font-medium">Sedang</span>
    <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">Rendah</span>
    <span class="text-gray-400">— = Tidak terkait</span>
    <span id="save-indicator" class="ml-auto text-gray-400 hidden">Menyimpan...</span>
    <span id="save-ok" class="ml-auto text-green-600 hidden">✓ Tersimpan</span>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-auto max-h-[70vh]">
        <table class="text-xs border-collapse w-full">
            {{-- Header CPL --}}
            <thead>
                <tr class="bg-indigo-900 text-white">
                    <th class="sticky left-0 top-0 z-20 bg-indigo-900 px-3 py-2 text-left w-8 font-normal">#</th>
                    <th class="sticky left-8 top-0 z-20 bg-indigo-900 px-3 py-2 text-left min-w-[220px] font-semibold">Mata Kuliah</th>
                    @foreach($cpls as $cpl)
                    <th class="sticky top-0 z-10 bg-indigo-900 px-2 py-2 text-center w-24">
                        <span class="font-bold">{{ $cpl->kode }}</span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mataKuliahs as $mk)
                <tr class="border-b border-gray-100 hover:bg-indigo-50/30 group">
                    <td class="sticky left-0 z-10 bg-white group-hover:bg-indigo-50/30 px-3 py-1.5 text-gray-400 text-right">{{ $loop->iteration }}</td>
                    <td class="sticky left-8 z-10 bg-white group-hover:bg-indigo-50/30 px-3 py-1.5 border-r border-gray-100">
                        <span class="font-mono text-indigo-600 mr-1">{{ $mk->kode_rps }}</span>
                        <span class="text-gray-800">{{ $mk->nama }}</span>
                        <span class="text-gray-400 ml-1">({{ $mk->sks }} SKS)</span>
                    </td>
                    @foreach($cpls as $cpl)
                    @php $currentLevel = $matriksInput[$mk->id][$cpl->id] ?? ''; @endphp
                    <td class="px-1 py-1 text-center border-r border-gray-50">
                        <select
                            data-mk="{{ $mk->id }}"
                            data-cpl="{{ $cpl->id }}"
                            onchange="saveCell(this)"
                            class="cell-select w-full rounded px-1 py-0.5 text-xs border cursor-pointer focus:outline-none focus:ring-1 focus:ring-indigo-400
                                   {{ $currentLevel === 'Tinggi' ? 'bg-red-50 border-red-200 text-red-700' :
                                      ($currentLevel === 'Sedang' ? 'bg-yellow-50 border-yellow-200 text-yellow-700' :
                                      ($currentLevel === 'Rendah' ? 'bg-green-50 border-green-200 text-green-700' :
                                      'bg-gray-50 border-gray-200 text-gray-400')) }}">
                            <option value="" {{ $currentLevel === '' ? 'selected' : '' }}>—</option>
                            <option value="Tinggi" {{ $currentLevel === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Sedang" {{ $currentLevel === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Rendah" {{ $currentLevel === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
const SAVE_URL = '{{ route("kurikulum.api.matriks.save-cell") }}';

const colorMap = {
    'Tinggi': 'bg-red-50 border-red-200 text-red-700',
    'Sedang': 'bg-yellow-50 border-yellow-200 text-yellow-700',
    'Rendah': 'bg-green-50 border-green-200 text-green-700',
    '':       'bg-gray-50 border-gray-200 text-gray-400',
};

let saveTimer;
const indicator = document.getElementById('save-indicator');
const saveOk    = document.getElementById('save-ok');

function saveCell(select) {
    const mkId  = select.dataset.mk;
    const cplId = select.dataset.cpl;
    const level = select.value;

    // Update warna dropdown langsung
    select.className = select.className.replace(/bg-\S+ border-\S+ text-\S+/g, '').trim();
    select.className += ' ' + (colorMap[level] || colorMap['']);

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
                body: JSON.stringify({ mata_kuliah_id: mkId, cpl_id: cplId, level: level || null }),
            });
            if (res.ok) {
                indicator.classList.add('hidden');
                saveOk.classList.remove('hidden');
                setTimeout(() => saveOk.classList.add('hidden'), 2000);
            }
        } catch (e) {
            console.error(e);
        }
    }, 400);
}
</script>
@endsection
