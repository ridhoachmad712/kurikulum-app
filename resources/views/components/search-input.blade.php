@props(['placeholder' => 'Cari...'])

<form method="GET" class="relative group" x-data="{ focused: false }">

    {{-- Preserve existing query params (semester, program_studi_id, etc.) --}}
    @foreach(request()->except(['search', 'page']) as $key => $val)
    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
    @endforeach

    {{-- Search icon --}}
    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
        <svg class="w-4 h-4 transition-colors duration-200"
             :class="focused ? 'text-indigo-500' : 'text-gray-400'"
             fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>

    {{-- Input --}}
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="{{ $placeholder }}"
           autocomplete="off"
           spellcheck="false"
           @focus="focused = true"
           @blur="focused = false"
           class="h-9 w-56 rounded-full bg-gray-100 border border-transparent
                  pl-9 {{ request('search') ? 'pr-8' : 'pr-4' }} text-sm
                  text-gray-800 placeholder-gray-400
                  focus:outline-none focus:bg-white focus:border-indigo-300
                  focus:ring-2 focus:ring-gray-500/20 focus:w-64
                  transition-all duration-200 ease-in-out">

    {{-- Clear button — only when there is an active search --}}
    @if(request('search'))
    <a href="{{ url()->current() }}?{{ http_build_query(request()->except(['search', 'page'])) }}"
       title="Hapus pencarian"
       class="absolute inset-y-0 right-2.5 flex items-center">
        <span class="flex items-center justify-center w-4 h-4 rounded-full
                     bg-gray-400 hover:bg-gray-500 text-white
                     transition-colors duration-150">
            <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </span>
    </a>
    @endif

</form>
