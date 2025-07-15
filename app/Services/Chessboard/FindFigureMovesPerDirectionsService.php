<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\Direction;

readonly class FindFigureMovesPerDirectionsService
{
    public function __construct(private CheckCoordinatesValidityService $checkCoordinatesValidityService)
    {
    }

    /**
     * @param Cell[][] $field
     * @param Cell $selectedCell
     * @param list<Direction> $directions
     * @param bool $useLoop
     * @return Cell[]
     */
    public function run(array $field, Cell $selectedCell, array $directions, bool $useLoop = true): array
    {
        $list = [];

        foreach ($directions as $direction) {
            $x = $selectedCell->x + $direction->x;
            $y = $selectedCell->y + $direction->y;

            if (!$useLoop && $this->checkCoordinatesValidityService->run($x, $y)) {
                $cell = $field[$y][$x];

                if (!$cell->piece || $cell->piece->isWhite !== $selectedCell->piece?->isWhite) {
                    $list[] = $cell;
                }
                continue;
            }

            while ($useLoop && $this->checkCoordinatesValidityService->run($x, $y)) {
                $cell = $field[$y][$x];

                if ($cell->piece) {
                    if ($cell->piece->isWhite !== $selectedCell->piece?->isWhite) {
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
