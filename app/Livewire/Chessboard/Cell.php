<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Cell extends Component
{
    #[Locked]
    public CellDTO $cellDTO;

    public function render()
    {
        return view('livewire.chessboard.cell');
    }
}
