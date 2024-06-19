@props([
    'field',
    'isWhiteMove',
])

@php
    /** @var array<array<\App\DTOs\Chessboard\CellDTO>> $field */
    /** @var bool $isWhiteMove */
@endphp

<div id="chessboard" class="" x-data="cell" @next-move="nextMove($event)">
    <div class="flex justify-center items-center max-w-lg">
        @for($y = 0; $y < self::X_SIZE; $y++)
            <div class="w-16 text-center">
                {{ chr(ord('A') + $y) }}
            </div>
        @endfor
    </div>

    @for($y = 0; $y < self::X_SIZE; $y++)
        <div class="flex justify-start">
            <div class="flex max-w-lg">
                @for($x = 0; $x < self::Y_SIZE; $x++)
                    <div @click="select({{ $x }}, {{ $y }}, @js($field[$y][$x]->pieceDTO?->isWhite))">
                        @php
                            $cellKey = "chess-cell-$y-$x-"
                                . $field[$y][$x]->pieceDTO?->isWhite
                                . $field[$y][$x]->pieceDTO?->pieceType->value;
                        @endphp
                        <livewire:chessboard.cell
                            :cellDTO="$field[$y][$x]"
                            :key="$cellKey"
                        />
                    </div>
                @endfor

                <p class="w-8 ml-2 self-center text-center">{{ $y + 1 }}</p>
            </div>
        </div>
    @endfor
</div>

@script
<script>
    Alpine.data('cell', () => ({
        selectedCell: null,
        isWhiteMove: @js($isWhiteMove),

        select(x, y, pieceColor) {
            if (this.selectedCell && !(this.selectedCell.x === x && this.selectedCell.y === y)) {
                $wire.makeMove(this.selectedCell.x, this.selectedCell.y, x, y);
                this.selectedCell = null;
            } else if (pieceColor === this.isWhiteMove) {
                this.selectedCell = {x: x, y: y};
            }
        },

        nextMove(event) {
            this.isWhiteMove = event.detail.isWhiteMove;
        }
    }))
</script>
@endscript
