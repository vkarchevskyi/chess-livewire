<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\Data\Chessboard\Piece as PieceData;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Piece extends Component
{
    public ?PieceData $piece;

    public function render(): View
    {
        return view('livewire.chessboard.piece');
    }
}
