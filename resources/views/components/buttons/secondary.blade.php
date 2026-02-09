@props(['type' => 'button', 'size' => 'md', 'outline' => false])

@php
    $classes = $outline ? 'btn btn-outline-secondary' : 'btn btn-secondary';
    
    if($size == 'sm') $classes .= ' btn-sm';
    if($size == 'lg') $classes .= ' btn-lg';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>