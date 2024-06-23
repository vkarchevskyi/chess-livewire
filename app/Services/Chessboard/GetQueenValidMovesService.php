<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\DirectionDTO;

readonly class GetQueenValidMovesService
{
    public function __construct(
        private FindFigureMovesPerDirectionsService $findFigureMovesPerDirectionsService
    ) {
    }

    /**
     * @param CellDTO[][] $field
     * @param CellDTO $selectedCell
     * @return CellDTO[]
     */
    public function run(array $field, CellDTO $selectedCell): array
    {
        /** @var DirectionDTO[] $loopedDirections */
        $loopedDirections = DirectionDTO::collect([
            ...config('chess.move_directions.bishop'),
            ...config('chess.move_directions.rook')
        ]);

        /** @var DirectionDTO[] $singleMoveDirections */
        $singleMoveDirections = DirectionDTO::collect(config('chess.move_directions.king'));

        return [
            ...$this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $loopedDirections),
            ...$this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $singleMoveDirections, false),
        ];
    }
}
