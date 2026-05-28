@php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();
@endphp

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-[9999] flex flex-col
           bg-white dark:bg-gray-900
           border-r border-gray-200 dark:border-gray-800
           transition-all duration-300 ease-in-out"
    :class="{
        'w-[260px]': $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen,
        'w-[72px]':  !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen,
        'translate-x-0':           $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="$store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    {{-- ── Logo ── --}}
    <div class="flex items-center h-16 px-4 border-b border-gray-100 dark:border-gray-800 shrink-0 overflow-hidden">
        <div class="w-8 h-8 rounded-xl bg-brand-600 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zm5.99 7.176A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
            </svg>
        </div>
        <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
             x-transition:enter="transition-opacity duration-150 delay-75"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="ml-3 overflow-hidden">
            <p class="text-gray-900 dark:text-white font-bold text-sm font-display leading-tight truncate">Kurikulum Prodi</p>
            <p class="text-gray-400 dark:text-gray-500 text-xs truncate">{{ auth()->user()?->programStudi?->nama ?? '–' }}</p>
        </div>
    </div>

    {{-- ── Navigation ── --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden py-3 scrollbar-hide">
        <nav>
            @foreach ($menuGroups as $group)
            <div class="mb-3">
                {{-- Group label (hidden when collapsed) --}}
                <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                     class="px-4 mb-1.5 mt-2 first:mt-0">
                    <span class="menu-group-label">{{ $group['title'] }}</span>
                </div>
                {{-- Separator when collapsed --}}
                <div x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                     class="mx-3 my-2 border-t border-gray-100 dark:border-gray-800" x-cloak></div>

                <ul class="space-y-0.5 px-2">
                    @foreach ($group['items'] as $item)
                    <li>
                        <a href="{{ $item['path'] }}"
                           @if(!empty($item['external'])) target="_blank" rel="noopener" @endif
                           class="menu-item group {{ $item['active'] ? 'menu-item-active' : 'menu-item-inactive' }}"
                           :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'justify-center px-0' : ''">

                            {{-- Icon --}}
                            <span class="{{ $item['active'] ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }} shrink-0">
                                {!! \App\Helpers\MenuHelper::getIconSvg($item['icon']) !!}
                            </span>

                            {{-- Label --}}
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                  class="menu-item-text flex-1 truncate flex items-center gap-1.5">
                                {{ $item['name'] }}
                                @if(!empty($item['external']))
                                <svg class="w-3 h-3 opacity-40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                </svg>
                                @endif
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </nav>
    </div>

    {{-- ── Bottom: collapse toggle (desktop only) ── --}}
    <div class="border-t border-gray-100 dark:border-gray-800 p-3 shrink-0">
        <button @click="$store.sidebar.toggleExpanded()"
                class="hidden xl:flex w-full items-center gap-2 px-3 py-2 rounded-xl
                       text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800
                       hover:text-gray-600 dark:hover:text-gray-300
                       text-xs transition-all duration-150"
                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered) ? 'justify-center' : ''">
            <svg :class="$store.sidebar.isExpanded ? '' : 'rotate-180'"
                 class="w-4 h-4 transition-transform duration-300 shrink-0"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="text-xs font-medium">Ciutkan</span>
        </button>
    </div>
</aside>
