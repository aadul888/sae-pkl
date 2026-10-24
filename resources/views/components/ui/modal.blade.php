@props([
    'id',
    'title' => null,
])

<div id="{{ $id }}" class="modal-backdrop" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">
    <div class="modal-dialog">
        <div class="modal-header">
            @if ($title)
                <h3 id="{{ $id }}-title" class="modal-title">{{ $title }}</h3>
            @endif
            <button type="button" class="modal-close" onclick="window.SaeUI?.closeModal('{{ $id }}')" aria-label="Tutup modal">&times;</button>
        </div>
        <div class="modal-body">
            {{ $slot }}
        </div>
        @if (isset($footer))
            <div class="modal-footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
