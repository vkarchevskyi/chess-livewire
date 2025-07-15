<?php

declare(strict_types=1);

namespace App\Data\Chessboard;

use App\Enums\Chessboard\PieceType;
use Livewire\Wireable;
use Webmozart\Assert\Assert;

final readonly class Piece implements Wireable
{
    public function __construct(
        public bool $isWhite,
        public PieceType $type
    ) {
    }

    /**
     * @return array<string, bool|string>
     */
    public function toLivewire(): array
    {
        return [
            'isWhite' => $this->isWhite,
            'type' => $this->type->value,
        ];
    }

    /**
     * @param array<string, mixed> $value
     */
    public static function fromLivewire($value): Piece
    {
        $isWhite = $value['isWhite'] ?? null;
        $type = $value['type'] ?? null;

        Assert::boolean($isWhite);
        Assert::isInstanceOf($type, PieceType::class);

        return new Piece($isWhite, $type);
    }
}
