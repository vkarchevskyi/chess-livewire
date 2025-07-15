<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;

readonly class GetPawnValidMovesService
{
    /**
     * @param Cell[][] $field
     * @param Cell $cellDTO
     * @return Cell[]
     */
    public function run(array $field, Cell $cellDTO): array
    {
        // TODO: Add el passant.
        // This method should have an information about previous move in order to implement this.

        /** @var Cell[] $moves */
        $moves = [];
        $direction = $cellDTO->piece?->isWhite ? 1 : -1;

        if (!isset($field[$cellDTO->y + $direction]) || !isset($cellDTO->piece)) {
            return [];
        }

        if (!$field[$cellDTO->y + $direction][$cellDTO->x]->piece) {
            $moves[] = $field[$cellDTO->y + $direction][$cellDTO->x];

            if ($cellDTO->piece->isWhite && $cellDTO->y === 1 || !$cellDTO->piece->isWhite && $cellDTO->y === 6) {
                if (
                    isset($field[$cellDTO->y + 2 * $direction]) &&
                    !$field[$cellDTO->y + 2 * $direction][$cellDTO->x]->piece
                ) {
                    $moves[] = $field[$cellDTO->y + 2 * $direction][$cellDTO->x];
                }
            }
        }

        foreach ([$cellDTO->x - 1, $cellDTO->x + 1] as $x) {
            if ($x < 0 || $x >= 8) {
                continue;
            }

            $piece = $field[$cellDTO->y + $direction][$x]->piece;
            if ($piece && $piece->isWhite !== $cellDTO->piece->isWhite) {
                $moves[] = $field[$cellDTO->y + $direction][$x];
            }
        }

        return $moves;
    }
}
