@props([
    'pieceDTO'
])

@php
    /** @var \App\DTOs\Chessboard\PieceDTO $pieceDTO */
@endphp

<div class="text-6xl">
    <p>
        {{ $pieceDTO->pieceType->getView($pieceDTO->isWhite) }}
    </p>
</div>
