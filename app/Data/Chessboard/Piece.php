<?php

declare(strict_types=1);

namespace App\Data\Chessboard;

use App\Enums\Chessboard\PieceType;
use Livewire\Wireable;

final readonly class Piece implements Wireable
{
    public function __construct(
        public bool $isWhite,
        public PieceType $type
    ) {
    }

    public function toLivewire(): array
    {
        return [
            'isWhite' => $this->isWhite,
            'type' => $this->type->value,
        ];
    }

    public static function fromLivewire($value): static
    {
        return new Piece(
            $value['isWhite'],
            PieceType::from($value['type']),
        );
    }
}
