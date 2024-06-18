<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Services\Chessboard\GetInitializedBoardService;
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

    public function render()
    {
        return view('livewire.chessboard.chessboard');
    }
}
