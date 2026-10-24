@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'required' => false,
    'hint' => null,
])

<div {{ $attributes->merge(['class' => 'form-group']) }}>
    @if ($label)
        <label @if($name) for="{{ $name }}" @endif class="form-label">
            {{ $label }}
            @if ($required)
                <span style="color: var(--danger); font-weight: 700;">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint)
        <div class="form-hint">{{ $hint }}</div>
    @endif

    @php
        $errorMessage = $error ?? (isset($errors) && $name && $errors->has($name) ? $errors->first($name) : null);
    @endphp

    @if ($errorMessage)
        <div class="form-error">
            {{ $errorMessage }}
        </div>
    @endif
</div>
