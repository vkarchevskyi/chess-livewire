<?php

declare(strict_types=1);

namespace App\Enums\Chessboard;

enum PieceType: string
{
    case PAWN = 'pawn';

    case BISHOP = 'bishop';

    case KNIGHT = 'knight';

    case ROOK = 'rook';

    case QUEEN = 'queen';

    case KING = 'king';
}
