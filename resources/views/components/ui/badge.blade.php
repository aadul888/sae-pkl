@props([
    'type' => 'info',
    'role' => null,
])

@php
    $class = $role ? 'badge badge-' . $role : 'badge badge-' . $type;
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
