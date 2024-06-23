<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\PieceDTO;
use App\Enums\Chessboard\PieceType;

readonly class GetInitializedBoardService
{
    /**
     * @return CellDTO[][]
     */
    public function run(): array
    {
        $field = [];

        for ($y = 0; $y < 8; $y++) {
            $field[$y] = [];
            for ($x = 0; $x < 8; $x++) {
                $field[$y][$x] = new CellDTO($x, $y, ($y * 8 + $x) % 2 !== $y % 2);
            }
        }

        foreach ([1, 8 - 2] as $index => $y) {
            for ($x = 0; $x < 8; $x++) {
                $field[$y][$x]->pieceDTO = new PieceDTO($index === 0, PieceType::PAWN);
            }
        }

        $field[0][0]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $field[0][8 - 1]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $field[8 - 1][0]->pieceDTO = new PieceDTO(false, PieceType::ROOK);
        $field[8 - 1][8 - 1]->pieceDTO = new PieceDTO(false, PieceType::ROOK);

        $field[0][1]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $field[0][8 - 2]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $field[8 - 1][1]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);
        $field[8 - 1][8 - 2]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);

        $field[0][2]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $field[0][8 - 3]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $field[8 - 1][2]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);
        $field[8 - 1][8 - 3]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);

        $field[0][3]->pieceDTO = new PieceDTO(true, PieceType::QUEEN);
        $field[8 - 1][3]->pieceDTO = new PieceDTO(false, PieceType::QUEEN);

        $field[0][4]->pieceDTO = new PieceDTO(true, PieceType::KING);
        $field[8 - 1][4]->pieceDTO = new PieceDTO(false, PieceType::KING);

        return $field;
    }
}
