@props([
    'type' => 'info',
    'dismissible' => false,
])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type]) }} role="alert">
    <div class="alert-content">
        {{ $slot }}
    </div>
    @if ($dismissible)
        <button type="button" class="alert-close" data-dismiss="alert" aria-label="Tutup notifikasi">&times;</button>
    @endif
</div>
