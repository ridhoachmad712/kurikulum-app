@props(['active' => false])
<a {{ $attributes->merge(['class' => 'nav-item ' . ($active ? 'nav-item-active' : 'nav-item-default')]) }}>
    @isset($icon){{ $icon }}@endisset
    {{ $slot }}
</a>
