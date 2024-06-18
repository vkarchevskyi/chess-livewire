<div class="">
    @for($y = 0; $y < self::X_SIZE; $y++)
        <div class="flex">
            @for($x = 0; $x < self::Y_SIZE; $x++)
                <livewire:chessboard.cell :cellDTO="$field[$y][$x]"/>
            @endfor
        </div>
    @endfor
</div>
