@props([
    'cellDTO',
    'isSelected',
    'isAvailableForMove',
])

@php
    /** @var \App\DTOs\Chessboard\CellDTO $cellDTO */
@endphp

<div
    @class([
        'w-16 h-16',
        'bg-gray-100' => $cellDTO->isWhite && !$isSelected && !$isAvailableForMove,
        'bg-blue-500' => !$cellDTO->isWhite && !$isSelected && !$isAvailableForMove,
        'bg-purple-300' => $isSelected,
        'bg-green-300' => $isAvailableForMove && !isset($cellDTO->pieceDTO),
        'bg-red-300' => $isAvailableForMove && $cellDTO->pieceDTO,
    ])
>
    <div class="mx-auto flex justify-center">
        @if(isset($cellDTO->pieceDTO))
            <livewire:chessboard.piece
                :pieceDTO="$cellDTO->pieceDTO"
                :key="'chess-piece-' . $cellDTO->pieceDTO->isWhite . '-' . $cellDTO->pieceDTO->pieceType->value"
            />
        @endif
    </div>
</div>
