@use('App\DTOs\Chessboard\CellDTO')

@props([
    'field',
    'isWhiteMove',
])

@php
    /** @var CellDTO[][] $field */
    /** @var bool $isWhiteMove */
@endphp

<div id="chessboard">
    <div class="flex justify-center items-center max-w-lg">
        @for($y = 0; $y < 8; $y++)
            <div class="w-16 text-center">
                {{ chr(ord('A') + $y) }}
            </div>
        @endfor
    </div>

    @for($y = 0; $y < 8; $y++)
        <div class="flex justify-start">
            <div class="flex max-w-lg">
                @for($x = 0; $x < 8; $x++)
                    <div
                        wire:click="selectCell({{ $x }}, {{ $y }})"
                    >
                        @php
                            $cellKey = sprintf(
                                'chess-cell-%d-%d-%d-%s-%s-%s',
                                $y,
                                $x,
                                $field[$y][$x]->pieceDTO?->isWhite,
                                $field[$y][$x]->pieceDTO?->pieceType->value,
                                $selectedCell?->x,
                                $selectedCell?->y
                            );

                            $isAvailableForMove = $availableMoves
                                ->filter(fn (CellDTO $cellDTO) => $cellDTO->x === $x && $cellDTO->y === $y)
                                ->count() >= 1;
                        @endphp

                        <livewire:chessboard.cell
                            :cellDTO="$field[$y][$x]"
                            :key="$cellKey"
                            :isSelected="$selectedCell?->x === $x && $selectedCell?->y === $y"
                            :isAvailableForMove="$isAvailableForMove"
                        />
                    </div>
                @endfor

                <p class="w-8 ml-2 self-center text-center">{{ $y + 1 }}</p>
            </div>
        </div>
    @endfor
</div>
