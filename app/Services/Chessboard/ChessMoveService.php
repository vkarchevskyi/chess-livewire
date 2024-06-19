<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Enums\Chessboard\PieceType;

readonly class ChessMoveService
{
    /**
     * @param CellDTO[][] $field
     * @param GetPawnValidMovesService $getPawnValidMovesService
     */
    public function __construct(
        private array $field,
        private GetPawnValidMovesService $getPawnValidMovesService,
    ) {
    }

    /**
     * @param CellDTO $cellDTO
     * @return CellDTO[]
     */
    public function getAvailableMoves(CellDTO $cellDTO): array
    {
        return match ($cellDTO->pieceDTO?->pieceType) {
            PieceType::PAWN => $this->getPawnValidMovesService->run($this->field, $cellDTO),
            default => [],
        };
    }

    /**
     * @param CellDTO $from
     * @param CellDTO $to
     * @return bool
     */
    public function isMoveValid(CellDTO $from, CellDTO $to): bool
    {
        $cells = $this->getAvailableMoves($from);

        foreach ($cells as $cell) {
            if ($cell->x === $to->x && $cell->y === $to->y) {
                return true;
            }
        }

        return false;
    }
}
