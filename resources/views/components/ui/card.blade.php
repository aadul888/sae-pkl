@props(['title' => null, 'subtitle' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if ($title || $subtitle)
        <div class="card-header">
            <div>
                @if ($title)
                    <h3 class="card-title">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="card-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            {{ $headerAction ?? '' }}
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if ($footer || isset($footerSlot))
        <div class="card-footer">
            {{ $footer ?? $footerSlot }}
        </div>
    @endif
</div>
