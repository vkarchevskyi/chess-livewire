<?php

declare(strict_types=1);

namespace App\Services\Chessboard\ExtraRules;

use App\DTOs\Chessboard\PieceDTO;
use App\Enums\Chessboard\PieceType;

final readonly class CheckPromotionService
{
    public function run(PieceDTO $piece, int $nextYCoordinate): bool
    {
        return $piece->pieceType === PieceType::PAWN
            && (
                $piece->isWhite && $nextYCoordinate === 7
                || !$piece->isWhite && $nextYCoordinate === 0
            );
    }
}
