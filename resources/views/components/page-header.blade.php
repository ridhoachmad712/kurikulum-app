@props([
    'title',
    'subtitle' => null,
    'back'     => null,
    'backLabel'=> 'Kembali',
])

<div class="mb-6">
    {{-- Breadcrumb / back link --}}
    @if($back)
    <nav class="flex items-center gap-1 text-xs text-gray-400 mb-2">
        <a href="{{ $back }}"
           class="hover:text-indigo-600 flex items-center gap-1 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ $backLabel }}
        </a>
    </nav>
    @endif

    {{-- Title row --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-lg font-bold text-gray-800 leading-tight">{{ $title }}</h1>
            @if($subtitle)
            <p class="text-sm text-gray-400 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        @isset($actions)
        <div class="flex items-center gap-2 flex-wrap shrink-0">
            {{ $actions }}
        </div>
        @endisset
    </div>
</div>
