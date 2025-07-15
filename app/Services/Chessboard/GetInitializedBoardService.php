<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\Data\Chessboard\Cell;
use App\Data\Chessboard\Piece;
use App\Enums\Chessboard\PieceType;

readonly class GetInitializedBoardService
{
    /**
     * @return Cell[][]
     */
    public function run(): array
    {
        $field = [];

        for ($y = 0; $y < 8; $y++) {
            $field[$y] = [];
            for ($x = 0; $x < 8; $x++) {
                $field[$y][$x] = new Cell($x, $y, ($y * 8 + $x) % 2 !== $y % 2);
            }
        }

        foreach ([1, 8 - 2] as $index => $y) {
            for ($x = 0; $x < 8; $x++) {
                $field[$y][$x]->piece = new Piece($index === 0, PieceType::PAWN);
            }
        }

        $field[0][0]->piece = new Piece(true, PieceType::ROOK);
        $field[0][8 - 1]->piece = new Piece(true, PieceType::ROOK);
        $field[8 - 1][0]->piece = new Piece(false, PieceType::ROOK);
        $field[8 - 1][8 - 1]->piece = new Piece(false, PieceType::ROOK);

        $field[0][1]->piece = new Piece(true, PieceType::KNIGHT);
        $field[0][8 - 2]->piece = new Piece(true, PieceType::KNIGHT);
        $field[8 - 1][1]->piece = new Piece(false, PieceType::KNIGHT);
        $field[8 - 1][8 - 2]->piece = new Piece(false, PieceType::KNIGHT);

        $field[0][2]->piece = new Piece(true, PieceType::BISHOP);
        $field[0][8 - 3]->piece = new Piece(true, PieceType::BISHOP);
        $field[8 - 1][2]->piece = new Piece(false, PieceType::BISHOP);
        $field[8 - 1][8 - 3]->piece = new Piece(false, PieceType::BISHOP);

        $field[0][3]->piece = new Piece(true, PieceType::QUEEN);
        $field[8 - 1][3]->piece = new Piece(false, PieceType::QUEEN);

        $field[0][4]->piece = new Piece(true, PieceType::KING);
        $field[8 - 1][4]->piece = new Piece(false, PieceType::KING);

        return $field;
    }
}
