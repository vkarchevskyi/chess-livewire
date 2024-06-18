<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\PieceDTO;
use Livewire\Component;

class Piece extends Component
{
    public PieceDTO $pieceDTO;

    public function render()
    {
        return view('livewire.chessboard.piece');
    }
}
