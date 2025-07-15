@props([
    'cell',
    'isSelected',
    'isAvailableForMove',
])

@php
    /** @var \App\Data\Chessboard\Cell $cell */
@endphp

<div
    @class([
        'w-16 h-16',
        'bg-gray-100' => $cell->isWhite && !$isSelected && !$isAvailableForMove,
        'bg-blue-500' => !$cell->isWhite && !$isSelected && !$isAvailableForMove,
        'bg-purple-300' => $isSelected,
        'bg-green-300' => $isAvailableForMove && !isset($cell->piece),
        'bg-red-300' => $isAvailableForMove && $cell->piece,
    ])
>
    <div class="mx-auto flex justify-center">
        @if(isset($cell->piece))
            <livewire:chessboard.piece
                :piece="$cell->piece"
                :key="'chess-piece-' . $cell->piece->isWhite . '-' . $cell->piece->type->value"
            />
        @endif
    </div>
</div>
