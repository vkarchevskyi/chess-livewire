<?php

declare(strict_types=1);

namespace App\DTOs\Chessboard;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

class CellDTO extends Data implements Wireable
{
    use WireableData;

    public function __construct(
        public readonly bool $isWhite,
        public ?PieceDTO $pieceDTO,
    ) {
    }
}
