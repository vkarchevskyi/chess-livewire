<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Services\Chessboard\CheckCoordinatesValidityService;
use App\Services\Chessboard\ChessMoveService;
use App\Services\Chessboard\GetInitializedBoardService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Chessboard extends Component
{
    protected CheckCoordinatesValidityService $checkCoordinatesValidityService;

    /**
     * @var CellDTO[][]
     */
    public array $field;

    #[Locked]
    public bool $isWhiteMove = true;

    public function boot(): void
    {
        $this->checkCoordinatesValidityService = app(CheckCoordinatesValidityService::class);
    }

    public function mount(GetInitializedBoardService $getInitializedBoardService): void
    {
        $this->field = $getInitializedBoardService->run();
    }

    public function makeMove(int $fromX, int $fromY, int $toX, int $toY): void
    {
        /** @var ChessMoveService $chessMoveService */
        $chessMoveService = app(ChessMoveService::class, ['field' => $this->field]);

        if (
            !$this->checkCoordinatesValidityService->run($fromX, $fromY) ||
            !$this->checkCoordinatesValidityService->run($toX, $toY) ||

            !isset($this->field[$fromY][$fromX]->pieceDTO) ||
            $this->field[$fromY][$fromX]->pieceDTO->isWhite !== $this->isWhiteMove ||

            !$chessMoveService->isMoveValid($this->field[$fromY][$fromX], $this->field[$toY][$toX])
        ) {
            $this->skipRender();
            return;
        }

        $this->field[$toY][$toX]->pieceDTO = $this->field[$fromY][$fromX]->pieceDTO;
        $this->field[$fromY][$fromX]->pieceDTO = null;

        $this->isWhiteMove = !$this->isWhiteMove;

        $this->dispatch('next-move', isWhiteMove: $this->isWhiteMove);
    }

    public function render(): View
    {
        return view('livewire.chessboard.chessboard');
    }
}
