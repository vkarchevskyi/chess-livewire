<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Enums\Chessboard\PieceType;
use Illuminate\Support\Collection;
use LogicException;

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
     * @return Collection<int, CellDTO>
     */
    public function getValidMoves(CellDTO $cellDTO): Collection
    {
        return collect($this->getAvailableMoves($this->field, $cellDTO))->filter(
            function (CellDTO $move) use ($cellDTO): bool {
                $tempField = [];
                $decodedField = json_decode(json_encode($this->field), true);

                for ($y = 0; $y < 8; $y++) {
                    $tempField[$y] = [];
                    for ($x = 0; $x < 8; $x++) {
                        $tempField[$y][$x] = CellDTO::from($decodedField[$y][$x]);
                    }
                }

                // TODO: need to change for custom moves (castle, el passant)
                $tempField[$move->y][$move->x]->pieceDTO = $tempField[$cellDTO->y][$cellDTO->x]->pieceDTO;
                $tempField[$cellDTO->y][$cellDTO->x]->pieceDTO = null;

                return !$this->isKingUnderCheck($cellDTO->pieceDTO?->isWhite, $tempField);
            }
        );
    }

    /**
     * @param CellDTO $from
     * @param CellDTO $to
     * @return bool
     */
    public function isMoveValid(CellDTO $from, CellDTO $to): bool
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
        /** @var CellDTO[] $pieces */
        $pieces = [];

        /** @var CellDTO[] $validMoves */
        $validMoves = [];

        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $pieces[] = $this->field[$y][$x];
            }
        }

        for ($i = 0; $i < 8 * 8; $i++) {
            if ($pieces[$i]->pieceDTO?->isWhite !== $sideColor) {
                continue;
            }

            array_push($validMoves, ...$this->getValidMoves($pieces[$i]));
        }

        return count($validMoves) === 0;
    }

    /**
     * @param CellDTO[][] $field
     * @param bool $whiteKing
     * @return CellDTO
     */
    private function findKingCell(array $field, bool $whiteKing): CellDTO
    {
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $piece = $field[$y][$x]->pieceDTO;

                if ($piece?->pieceType === PieceType::KING && $piece?->isWhite === $whiteKing) {
                    return $field[$y][$x];
                }
            }
        }

        throw new LogicException('There are no ' . ($whiteKing ? 'white' : 'black') . ' king on the field');
    }

    /**
     * @param CellDTO[][] $field
     * @param CellDTO $cellDTO
     * @return bool
     */
    private function isCellIsUnderAttack(array $field, CellDTO $cellDTO): bool
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
     * @param CellDTO[][] $field
     * @param CellDTO $cellDTO
     * @return CellDTO[]
     */
    private function getAvailableMoves(array $field, CellDTO $cellDTO): array
    {
        return match ($cellDTO->pieceDTO?->pieceType) {
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
