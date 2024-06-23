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
     * @param GetBishopValidMovesService $getBishopValidMovesService
     * @param GetKnightValidMovesService $getKnightValidMovesService
     * @param GetRookValidMovesService $getRookValidMovesService
     * @param GetQueenValidMovesService $getQueenValidMovesService
     * @param GetKingValidMovesService $getKingValidMovesService
     */
    public function __construct(
        private array $field,
        private GetPawnValidMovesService $getPawnValidMovesService,
        private GetBishopValidMovesService $getBishopValidMovesService,
        private GetKnightValidMovesService $getKnightValidMovesService,
        private GetRookValidMovesService $getRookValidMovesService,
        private GetQueenValidMovesService $getQueenValidMovesService,
        private GetKingValidMovesService $getKingValidMovesService,
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
            PieceType::KNIGHT => $this->getKnightValidMovesService->run($this->field, $cellDTO),
            PieceType::BISHOP => $this->getBishopValidMovesService->run($this->field, $cellDTO),
            PieceType::ROOK => $this->getRookValidMovesService->run($this->field, $cellDTO),
            PieceType::QUEEN => $this->getQueenValidMovesService->run($this->field, $cellDTO),
            PieceType::KING => $this->getKingValidMovesService->run($this->field, $cellDTO),
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
