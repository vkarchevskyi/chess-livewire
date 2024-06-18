<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\PieceDTO;
use App\Enums\Chessboard\PieceType;

readonly class GetInitializedBoardService
{
    /**
     * @param int $xSize Field's width
     * @param int $ySize Field's height
     */
    public function __construct(private int $xSize = 8, private int $ySize = 8)
    {
    }

    /**
     * @return array<array<CellDTO>>
     */
    public function run(): array
    {
        $field = [];

        for ($y = 0; $y < $this->xSize; $y++) {
            $field[$y] = [];
            for ($x = 0; $x < $this->ySize; $x++) {
                $field[$y][$x] = CellDTO::from(['isWhite' => ($y * $this->ySize + $x) % 2 === $y % 2]);
            }
        }

        foreach ([0 + 1, $this->ySize - 2] as $index => $y) {
            for ($x = 0; $x < $this->xSize; $x++) {
                $field[$y][$x]->pieceDTO = new PieceDTO($index === 0, PieceType::PAWN);
            }
        }

        $field[0][0]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $field[0][$this->xSize - 1]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $field[$this->ySize - 1][0]->pieceDTO = new PieceDTO(false, PieceType::ROOK);
        $field[$this->ySize - 1][$this->xSize - 1]->pieceDTO = new PieceDTO(false, PieceType::ROOK);

        $field[0][0 + 1]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $field[0][$this->xSize - 2]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $field[$this->ySize - 1][0 + 1]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);
        $field[$this->ySize - 1][$this->xSize - 2]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);

        $field[0][0 + 2]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $field[0][$this->xSize - 3]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $field[$this->ySize - 1][0 + 2]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);
        $field[$this->ySize - 1][$this->xSize - 3]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);

        $field[0][0 + 3]->pieceDTO = new PieceDTO(true, PieceType::QUEEN);
        $field[$this->ySize - 1][0 + 3]->pieceDTO = new PieceDTO(false, PieceType::QUEEN);

        $field[0][0 + 4]->pieceDTO = new PieceDTO(true, PieceType::KING);
        $field[$this->ySize - 1][0 + 4]->pieceDTO = new PieceDTO(false, PieceType::KING);

        return $field;
    }
}
