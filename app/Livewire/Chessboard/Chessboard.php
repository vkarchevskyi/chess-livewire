<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Services\Chessboard\GetInitializedBoardService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Chessboard extends Component
{
    public const int X_SIZE = 8;

    public const int Y_SIZE = 8;

    /**
     * @var array<array<CellDTO>>
     */
    public array $field;

    public function mount(GetInitializedBoardService $getInitializedBoardService): void
    {
        $this->field = $getInitializedBoardService->run();
    }

    public function makeMove(int $fromX, int $fromY, int $toX, int $toY): void
    {
        if (($fromX < 0 || $fromX >= self::X_SIZE) ||
            ($fromY < 0 || $fromY >= self::Y_SIZE) ||
            ($toX < 0 || $toX >= self::X_SIZE) ||
            ($toY < 0 || $toY >= self::Y_SIZE) ||
            !isset($this->field[$fromY][$fromX]->pieceDTO)
        ) {
            return;
        }

        $this->field[$toY][$toX]->pieceDTO = $this->field[$fromY][$fromX]->pieceDTO;
        $this->field[$fromY][$fromX]->pieceDTO = null;
    }

    public function render(): View
    {
        return view('livewire.chessboard.chessboard');
    }
}
