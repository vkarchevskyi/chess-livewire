<?php

declare(strict_types=1);

namespace App\DTOs\Chessboard;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class DirectionDTO extends Data
{
    public int $x;

    public int $y;

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
