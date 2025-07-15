@props([
    'piece'
])

@php
    /** @var \App\Data\Chessboard\Piece $piece */
@endphp

<div class="text-6xl">
    <p>
        {{ $piece->type->getView($piece->isWhite) }}
    </p>
</div>
