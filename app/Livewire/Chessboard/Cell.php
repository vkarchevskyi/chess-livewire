<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Cell extends Component
{
    #[Locked]
    public CellDTO $cellDTO;

    public bool $isSelected;

    public bool $isAvailableForMove;

    public function render(): View
    {
        return view('livewire.chessboard.cell');
    }
}
