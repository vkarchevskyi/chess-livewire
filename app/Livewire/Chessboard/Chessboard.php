<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\PieceDTO;
use App\Enums\Chessboard\PieceType;
use Livewire\Component;

class Chessboard extends Component
{
    public const int X_SIZE = 8;

    public const int Y_SIZE = 8;

    /**
     * @var array<array<CellDTO>>
     */
    public array $field;

    public function mount(): void
    {
        for ($y = 0; $y < self::X_SIZE; $y++) {
            $this->field[$y] = [];
            for ($x = 0; $x < self::Y_SIZE; $x++) {
                $this->field[$y][$x] = CellDTO::from(['isWhite' => ($y * self::Y_SIZE + $x) % 2 === $y % 2]);
            }
        }

        foreach ([0 + 1, self::Y_SIZE - 2] as $index => $y) {
            for ($x = 0; $x < self::X_SIZE; $x++) {
                $this->field[$y][$x]->pieceDTO = new PieceDTO($index === 0, PieceType::PAWN);
            }
        }

        $this->field[0][0]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $this->field[0][self::X_SIZE - 1]->pieceDTO = new PieceDTO(true, PieceType::ROOK);
        $this->field[self::Y_SIZE - 1][0]->pieceDTO = new PieceDTO(false, PieceType::ROOK);
        $this->field[self::Y_SIZE - 1][self::X_SIZE - 1]->pieceDTO = new PieceDTO(false, PieceType::ROOK);

        $this->field[0][0 + 1]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $this->field[0][self::X_SIZE - 2]->pieceDTO = new PieceDTO(true, PieceType::KNIGHT);
        $this->field[self::Y_SIZE - 1][0 + 1]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);
        $this->field[self::Y_SIZE - 1][self::X_SIZE - 2]->pieceDTO = new PieceDTO(false, PieceType::KNIGHT);

        $this->field[0][0 + 2]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $this->field[0][self::X_SIZE - 3]->pieceDTO = new PieceDTO(true, PieceType::BISHOP);
        $this->field[self::Y_SIZE - 1][0 + 2]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);
        $this->field[self::Y_SIZE - 1][self::X_SIZE - 3]->pieceDTO = new PieceDTO(false, PieceType::BISHOP);

        $this->field[0][0 + 3]->pieceDTO = new PieceDTO(true, PieceType::QUEEN);
        $this->field[self::Y_SIZE - 1][0 + 3]->pieceDTO = new PieceDTO(false, PieceType::QUEEN);

        $this->field[0][0 + 4]->pieceDTO = new PieceDTO(true, PieceType::KING);
        $this->field[self::Y_SIZE - 1][0 + 4]->pieceDTO = new PieceDTO(false, PieceType::KING);
    }

    public function render()
    {
        return view('livewire.chessboard.chessboard');
    }
}
