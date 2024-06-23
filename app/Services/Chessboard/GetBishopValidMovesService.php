<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\DirectionDTO;

readonly class GetBishopValidMovesService
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
        /** @var DirectionDTO[] $directions */
        $directions = DirectionDTO::collect(config('chess.move_directions.bishop'));

        return $this->findFigureMovesPerDirectionsService->run($field, $selectedCell, $directions);
    }
}
