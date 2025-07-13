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

    public function getView(bool $isWhite): string
    {
        return match ($this) {
            self::PAWN => $isWhite ? '♙' : '♟︎',
            self::BISHOP => $isWhite ? '♗' : '♝',
            self::KNIGHT => $isWhite ? '♘' : '♞',
            self::ROOK => $isWhite ? '♖' : '♜',
            self::QUEEN => $isWhite ? '♕' : '♛',
            self::KING => $isWhite ? '♔' : '♚',
        };
    }

    public function getLabel(): string
    {
        return mb_ucfirst($this->value);
    }
}
