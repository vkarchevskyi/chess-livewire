<?php

declare(strict_types=1);

namespace App\Services\Chessboard\ExtraRules;

use App\Data\Chessboard\Piece;
use App\Enums\Chessboard\PieceType;

final readonly class CheckPromotionService
{
    public function run(Piece $piece, int $nextYCoordinate): bool
    {
        return $piece->type === PieceType::PAWN
            && (
                $piece->isWhite && $nextYCoordinate === 7
                || !$piece->isWhite && $nextYCoordinate === 0
            );
    }
}
