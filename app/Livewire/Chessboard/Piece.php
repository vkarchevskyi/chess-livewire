<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\PieceDTO;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Piece extends Component
{
    public ?PieceDTO $pieceDTO;

    public function render(): View
    {
        return view('livewire.chessboard.piece');
    }
}
