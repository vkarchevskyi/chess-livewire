<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\Direction;

readonly class GetBishopValidMovesService
{
    public function __construct(
        private FindFigureMovesPerDirectionsService $findFigureMovesPerDirectionsService
    ) {
    }

    /**
     * @param Cell[][] $field
     * @param Cell $selectedCell
     * @return Cell[]
     */
    public function run(array $field, Cell $selectedCell): array
    {
        $directions = Direction::collect(config('chess.move_directions.bishop'));

        return $this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $directions);
    }
}
