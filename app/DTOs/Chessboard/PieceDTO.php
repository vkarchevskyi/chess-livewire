<?php

declare(strict_types=1);

namespace App\DTOs\Chessboard;

use App\Enums\Chessboard\PieceType;
use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

class PieceDTO extends Data implements Wireable
{
    use WireableData;

    public function __construct(
        public bool $isWhite,
        public PieceType $pieceType
    ) {
    }
}
