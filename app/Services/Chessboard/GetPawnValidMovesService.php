<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;

readonly class GetPawnValidMovesService
{
    /**
     * @param CellDTO[][] $field
     * @param CellDTO $cellDTO
     * @return CellDTO[]
     */
    public function run(array $field, CellDTO $cellDTO): array
    {
        // TODO: add promotion
        // TODO: Add el passant

        /** @var CellDTO[] $moves */
        $moves = [];
        $direction = $cellDTO->pieceDTO?->isWhite ? 1 : -1;

        if (!isset($field[$cellDTO->y + $direction]) || !isset($cellDTO->pieceDTO)) {
            return [];
        }

        if (!$field[$cellDTO->y + $direction][$cellDTO->x]->pieceDTO) {
            $moves[] = $field[$cellDTO->y + $direction][$cellDTO->x];

            if ($cellDTO->pieceDTO->isWhite && $cellDTO->y === 1 || !$cellDTO->pieceDTO->isWhite && $cellDTO->y === 6) {
                if (
                    isset($field[$cellDTO->y + 2 * $direction]) &&
                    !$field[$cellDTO->y + 2 * $direction][$cellDTO->x]->pieceDTO
                ) {
                    $moves[] = $field[$cellDTO->y + 2 * $direction][$cellDTO->x];
                }
            }
        }

        foreach ([$cellDTO->x - 1, $cellDTO->x + 1] as $x) {
            if ($x < 0 || $x >= 8) {
                continue;
            }

            $piece = $field[$cellDTO->y + $direction][$x]->pieceDTO;
            if ($piece && $piece->isWhite !== $cellDTO->pieceDTO->isWhite) {
                $moves[] = $field[$cellDTO->y + $direction][$x];
            }
        }

        return $moves;
    }
}
