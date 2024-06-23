<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\DirectionDTO;

readonly class FindFigureMovesPerDirectionsService
{
    public function __construct(private CheckCoordinatesValidityService $checkCoordinatesValidityService)
    {
    }

    /**
     * @param CellDTO[][] $field
     * @param CellDTO $selectedCell
     * @param DirectionDTO[] $directions
     * @param bool $useLoop
     * @return CellDTO[]
     */
    public function run(array $field, CellDTO $selectedCell, array $directions, bool $useLoop = true): array
    {
        $list = [];

        foreach ($directions as $direction) {
            $x = $selectedCell->x + $direction->x;
            $y = $selectedCell->y + $direction->y;

            if (!$useLoop && $this->checkCoordinatesValidityService->run($x, $y)) {
                $cell = $field[$y][$x];

                if (!$cell->pieceDTO || $cell->pieceDTO->isWhite !== $selectedCell->pieceDTO?->isWhite) {
                    $list[] = $cell;
                }
                continue;
            }

            while ($useLoop && $this->checkCoordinatesValidityService->run($x, $y)) {
                $cell = $field[$y][$x];

                if ($cell->pieceDTO) {
                    if ($cell->pieceDTO->isWhite !== $selectedCell->pieceDTO?->isWhite) {
                        $list[] = $cell;
                    }

                    break;
                }

                $list[] = $cell;
                $x += $direction->x;
                $y += $direction->y;
            }
        }

        return $list;
    }
}
