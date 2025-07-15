<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\Piece;
use App\Enums\Chessboard\PieceType;
use Illuminate\Support\Collection;
use LogicException;

readonly class ChessMoveService
{
    /**
     * @param Cell[][] $field
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
     * @param Cell $cellDTO
     * @return Collection<int, Cell>
     */
    public function getValidMoves(Cell $cellDTO): Collection
    {
        return collect($this->getAvailableMoves($this->field, $cellDTO))->filter(
            function (Cell $move) use ($cellDTO): bool {
                $tempField = [];

                for ($y = 0; $y < 8; $y++) {
                    $tempField[$y] = [];
                    for ($x = 0; $x < 8; $x++) {
                        $cell = $this->field[$y][$x];
                        $piece = $cell->piece ? new Piece($cell->piece->isWhite, $cell->piece->type) : null;

                        $tempField[$y][$x] = new Cell($cell->x, $cell->y, $cell->isWhite, $piece);
                    }
                }

                // TODO: need to change for custom moves (castle, el passant)
                $tempField[$move->y][$move->x]->piece = $tempField[$cellDTO->y][$cellDTO->x]->piece;
                $tempField[$cellDTO->y][$cellDTO->x]->piece = null;

                return !$this->isKingUnderCheck($cellDTO->piece?->isWhite, $tempField);
            }
        );
    }

    /**
     * @param Cell $from
     * @param Cell $to
     * @return bool
     */
    public function isMoveValid(Cell $from, Cell $to): bool
    {
        $cells = $this->getValidMoves($from);

        foreach ($cells as $cell) {
            if ($cell->x === $to->x && $cell->y === $to->y) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param bool $whiteKing
     * @param array|null $field
     * @return bool
     */
    public function isKingUnderCheck(bool $whiteKing, ?array $field = null): bool
    {
        if (is_null($field)) {
            $field = $this->field;
        }

        return $this->isCellIsUnderAttack($field, $this->findKingCell($field, $whiteKing));
    }

    public function isMated(bool $sideColor): bool
    {
        /** @var Cell[] $pieces */
        $pieces = [];

        /** @var Cell[] $validMoves */
        $validMoves = [];

        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $pieces[] = $this->field[$y][$x];
            }
        }

        for ($i = 0; $i < 8 * 8; $i++) {
            if ($pieces[$i]->piece?->isWhite !== $sideColor) {
                continue;
            }

            array_push($validMoves, ...$this->getValidMoves($pieces[$i]));
        }

        return count($validMoves) === 0;
    }

    /**
     * @param Cell[][] $field
     * @param bool $whiteKing
     * @return Cell
     */
    private function findKingCell(array $field, bool $whiteKing): Cell
    {
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $piece = $field[$y][$x]->piece;

                if ($piece?->type === PieceType::KING && $piece?->isWhite === $whiteKing) {
                    return $field[$y][$x];
                }
            }
        }

        throw new LogicException('There are no ' . ($whiteKing ? 'white' : 'black') . ' king on the field');
    }

    /**
     * @param Cell[][] $field
     * @param Cell $cellDTO
     * @return bool
     */
    private function isCellIsUnderAttack(array $field, Cell $cellDTO): bool
    {
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $availableMoves = $this->getAvailableMoves($field, $field[$y][$x]);
                foreach ($availableMoves as $availableMove) {
                    if ($availableMove->x === $cellDTO->x && $availableMove->y === $cellDTO->y) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param Cell[][] $field
     * @param Cell $cellDTO
     * @return Cell[]
     */
    private function getAvailableMoves(array $field, Cell $cellDTO): array
    {
        return match ($cellDTO->piece?->type) {
            PieceType::PAWN => $this->getPawnValidMovesService->run($field, $cellDTO),
            PieceType::KNIGHT => $this->getKnightValidMovesService->run($field, $cellDTO),
            PieceType::BISHOP => $this->getBishopValidMovesService->run($field, $cellDTO),
            PieceType::ROOK => $this->getRookValidMovesService->run($field, $cellDTO),
            PieceType::QUEEN => $this->getQueenValidMovesService->run($field, $cellDTO),
            PieceType::KING => $this->getKingValidMovesService->run($field, $cellDTO),
            default => [],
        };
    }
}
