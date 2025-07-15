<?php

declare(strict_types=1);

namespace App\Data\Chessboard;

use Livewire\Wireable;
use Webmozart\Assert\Assert;

final class Cell implements Wireable
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isWhite,
        public ?Piece $piece = null,
    ) {
    }

    /**
     * @return array<string, int|bool|array<string, bool|string>>
     */
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

    /**
     * @param array<string, mixed> $value
     */
    public static function fromLivewire($value): Cell
    {
        $piece = null;
        if (isset($value['piece']) && is_array($value['piece'])) {
            $piece = Piece::fromLivewire($value['piece']);
        }

        $x = $value['x'];
        $y = $value['y'];
        $isWhite = $value['isWhite'];

        Assert::integer($x);
        Assert::integer($y);
        Assert::boolean($isWhite);

        return new Cell($x, $y, $isWhite, $piece);
    }
}
