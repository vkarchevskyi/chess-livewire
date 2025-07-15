<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\Data\Chessboard\Cell as CellData;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Cell extends Component
{
    #[Locked]
    public CellData $cell;

    public bool $isSelected;

    public bool $isAvailableForMove;

    public function render(): View
    {
        return view('livewire.chessboard.cell');
    }
}
