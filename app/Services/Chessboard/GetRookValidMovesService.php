<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\DirectionData;

readonly class GetRookValidMovesService
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
        /** @var DirectionData[] $directions */
        $directions = DirectionData::collect(config('chess.move_directions.rook'));

        return $this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $directions);
    }
}
