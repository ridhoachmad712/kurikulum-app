@php
$colors = ['Tinggi'=>'bg-red-100 text-red-700','Sedang'=>'bg-yellow-100 text-yellow-700','Rendah'=>'bg-green-100 text-green-700'];
@endphp
<span class="px-1.5 py-0.5 rounded text-xs font-medium {{ $colors[$level] ?? 'bg-gray-100 text-gray-400' }}">
    {{ $level ?? '—' }}
</span>
