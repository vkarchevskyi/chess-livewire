<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\DTOs\Chessboard\PieceDTO;
use App\Enums\Chessboard\PieceType;
use App\Services\Chessboard\CheckCoordinatesValidityService;
use App\Services\Chessboard\ChessMoveService;
use App\Services\Chessboard\ExtraRules\CheckPromotionService;
use App\Services\Chessboard\GetInitializedBoardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Chessboard extends Component
{
    /**
     * @var CellDTO[][]
     */
    #[Locked]
    public array $field;

    #[Locked]
    public ?CellDTO $selectedCell;

    /**
     * @var Collection<int, CellDTO>
     */
    #[Locked]
    public Collection $availableMoves;

    #[Locked]
    public bool $isWhiteMove;

    #[Locked]
    public ?bool $isWhiteWin;

    #[Locked]
    public bool $isWhiteCastlingPossible;

    #[Locked]
    public bool $isBlackCastlingPossible;

    public function mount(): void
    {
        $this->initBoard();
    }

    public function render(): View
    {
        return view('livewire.chessboard.chessboard');
    }

    public function selectCell(
        int $x,
        int $y,
        CheckPromotionService $checkPromotionService,
        CheckCoordinatesValidityService $checkCoordinatesValidityService,
        ?string $pieceType = null,
    ): void {
        /** @var ChessMoveService $chessMoveService */
        $chessMoveService = app(ChessMoveService::class, ['field' => $this->field]);

        if (isset($this->isWhiteWin) || !$checkCoordinatesValidityService->run($x, $y)) {
            $this->skipRender();
            return;
        }

        if (!isset($this->selectedCell)) {
            $this->handleEmptySelectedCell($chessMoveService, $x, $y);
            return;
        }

        if (
            isset($this->field[$y][$x]->pieceDTO) &&
            $this->field[$y][$x]->pieceDTO->isWhite === $this->selectedCell->pieceDTO?->isWhite
        ) {
            $this->selectedCell = $this->field[$y][$x];
            $this->availableMoves = $chessMoveService->getValidMoves($this->selectedCell);
            return;
        }

        $fromX = $this->selectedCell->x;
        $fromY = $this->selectedCell->y;

        if (!$chessMoveService->isMoveValid($this->selectedCell, $this->field[$y][$x])) {
            $this->skipRender();
            return;
        }

        $piece = $this->field[$fromY][$fromX]->pieceDTO;
        if ($checkPromotionService->run($piece, $y)) {
            if (is_null($pieceType)) {
                $this->dispatch('show-promotion-modal', x: $x, y: $y);
                return;
            }

            $this->field[$y][$x]->pieceDTO = new PieceDTO($piece->isWhite, PieceType::from($pieceType));
        } else {
            // TODO: need to change for custom moves (castle, el passant)
            $this->field[$y][$x]->pieceDTO = $this->field[$fromY][$fromX]->pieceDTO;
        }

        // Remove piece from previous cell
        $this->field[$fromY][$fromX]->pieceDTO = null;

        $this->availableMoves = collect();
        $this->selectedCell = null;

        $this->isWhiteMove = !$this->isWhiteMove;

        if ($chessMoveService->isMated($this->isWhiteMove)) {
            $this->isWhiteWin = !$this->isWhiteMove;
        }
    }

    public function reinitBoard(): void
    {
        if (isset($this->isWhiteWin)) {
            $this->initBoard();
        }
    }

    /**
     * This method was made private for security reasons.
     *
     * @return void
     */
    private function initBoard(): void
    {
        /** @var GetInitializedBoardService $getInitializedBoardService */
        $getInitializedBoardService = app(GetInitializedBoardService::class);

        $this->availableMoves = collect();
        $this->field = $getInitializedBoardService->run();
        $this->isWhiteWin = null;
        $this->isWhiteMove = true;
        $this->selectedCell = null;
        $this->isWhiteCastlingPossible = true;
        $this->isBlackCastlingPossible = true;
    }

    private function handleEmptySelectedCell(ChessMoveService $chessMoveService, int $x, int $y): void
    {
        $containsPiece = isset($this->field[$y][$x]->pieceDTO);
        $containsPieceOfOppositeSide = $this->field[$y][$x]->pieceDTO?->isWhite !== $this->isWhiteMove;

        if (!$containsPiece || $containsPieceOfOppositeSide) {
            $this->skipRender();
        } else {
            $this->selectedCell = $this->field[$y][$x];
            $this->availableMoves = $chessMoveService->getValidMoves($this->selectedCell);
        }
    }
}
