<?php

declare(strict_types=1);

namespace App\DTOs\Chessboard;

use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CellDTO extends Data implements Wireable
{
    use WireableData;

    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isWhite,
        public ?PieceDTO $pieceDTO = null,
    ) {
    }

    /**
     * @param ValidationContext $context
     * @return array<string, string[]>
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'x' => ['required', 'integer', 'min:0', 'max:8'],
            'y' => ['required', 'integer', 'min:0', 'max:8'],
        ];
    }
}
