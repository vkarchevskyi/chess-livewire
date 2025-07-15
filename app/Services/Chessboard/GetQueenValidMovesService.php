<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\DirectionData;

readonly class GetQueenValidMovesService
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
        /** @var DirectionData[] $loopedDirections */
        $loopedDirections = DirectionData::collect([
            ...config('chess.move_directions.bishop'),
            ...config('chess.move_directions.rook')
        ]);

        /** @var DirectionData[] $singleMoveDirections */
        $singleMoveDirections = DirectionData::collect(config('chess.move_directions.king'));

        return [
            ...$this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $loopedDirections),
            ...$this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $singleMoveDirections, false),
        ];
    }
}
