@props(['type' => 'button', 'size' => 'md', 'fullWidth' => false])

@php
    $classes = 'btn btn-primary';
    
    if($size == 'sm') $classes .= ' btn-sm';
    if($size == 'lg') $classes .= ' btn-lg';
    if($fullWidth) $classes .= ' w-100';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>