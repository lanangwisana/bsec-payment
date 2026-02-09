@props(['type' => 'button', 'size' => 'md'])

@php
    $classes = 'btn btn-danger';
    
    if($size == 'sm') $classes .= ' btn-sm';
    if($size == 'lg') $classes .= ' btn-lg';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>