<?php

declare(strict_types=1);

namespace App\DTOs\Chessboard;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CellDTO extends Data implements Wireable
{
    use WireableData;

    public int $isWhite;

    public PieceDTO|Optional $pieceDTO;
}
