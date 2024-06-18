@props([
    'field'
])

<div id="chessboard" class="" x-data="{ selectedCell: null }">
    @for($y = 0; $y < self::X_SIZE; $y++)
        <div class="flex">
            @for($x = 0; $x < self::Y_SIZE; $x++)
                <div @click="
                    if (selectedCell && !(selectedCell.x === {{ $x }} && selectedCell.y === {{ $y }})) {
                        $wire.makeMove(selectedCell.x, selectedCell.y, {{ $x }}, {{ $y }});
                        selectedCell = null;
                    } else if (@js(isset($field[$y][$x]->pieceDTO))) {
                        selectedCell = { x: {{ $x }}, y: {{ $y }} };
                    }"
                >
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
        </div>
    @endfor
</div>
