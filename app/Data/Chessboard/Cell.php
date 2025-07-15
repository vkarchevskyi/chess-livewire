<?php

declare(strict_types=1);

namespace App\Data\Chessboard;

use Livewire\Wireable;

final class Cell implements Wireable
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isWhite,
        public ?Piece $piece = null,
    ) {
    }

    public function toLivewire(): array
    {
        $cellData = [
            'x' => $this->x,
            'y' => $this->y,
            'isWhite' => $this->isWhite,
        ];

        if ($this->piece) {
            $cellData['piece'] = [
                'isWhite' => $this->piece->isWhite,
                'type' => $this->piece->type
            ];
        }

        return $cellData;
    }

    public static function fromLivewire($value): static
    {
        $piece = null;
        if (isset($value['piece'])) {
            $piece = new Piece(
                $value['piece']['isWhite'],
                $value['piece']['type'],
            );
        }

        return new Cell(
            $value['x'],
            $value['y'],
            $value['isWhite'],
            $piece
        );
    }
}
