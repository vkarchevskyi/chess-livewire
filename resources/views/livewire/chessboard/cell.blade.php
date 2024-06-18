@use('Spatie\LaravelData\Optional')

@props([
    'cellDTO',
])

@php
    /** @var \App\DTOs\Chessboard\CellDTO $cellDTO */
@endphp

<div
    @class([
        'w-16 h-16',
        'bg-gray-100' => $cellDTO->isWhite,
        'bg-blue-500' => !$cellDTO->isWhite,
    ])
>
    <div class="mx-auto flex justify-center">
        @if(!($cellDTO->pieceDTO instanceof Optional))
            <livewire:chessboard.piece :pieceDTO="$cellDTO->pieceDTO"/>
        @endif
    </div>
</div>
