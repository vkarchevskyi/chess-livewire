<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Services\Chessboard\ChessMoveService;
use App\Services\Chessboard\GetInitializedBoardService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Chessboard extends Component
{
    public const int X_SIZE = 8;

    public const int Y_SIZE = 8;

    /**
     * @var array<array<CellDTO>>
     */
    public array $field;

    #[Locked]
    public bool $isWhiteMove = true;

    public function mount(GetInitializedBoardService $getInitializedBoardService): void
    {
        $this->field = $getInitializedBoardService->run();
    }

    public function makeMove(int $fromX, int $fromY, int $toX, int $toY): void
    {
        /** @var ChessMoveService $chessMoveService */
        $chessMoveService = app(ChessMoveService::class, ['field' => $this->field]);

        if (
            ($fromX < 0 || $fromX >= self::X_SIZE) ||
            ($fromY < 0 || $fromY >= self::Y_SIZE) ||
            ($toX < 0 || $toX >= self::X_SIZE) ||
            ($toY < 0 || $toY >= self::Y_SIZE) ||

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
