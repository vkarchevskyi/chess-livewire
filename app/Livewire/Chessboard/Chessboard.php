<?php

declare(strict_types=1);

namespace App\Livewire\Chessboard;

use App\DTOs\Chessboard\CellDTO;
use App\Services\Chessboard\CheckCoordinatesValidityService;
use App\Services\Chessboard\ChessMoveService;
use App\Services\Chessboard\GetInitializedBoardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Chessboard extends Component
{
    private CheckCoordinatesValidityService $checkCoordinatesValidityService;

    /**
     * @var CellDTO[][]
     */
    public array $field;

    #[Locked]
    public ?CellDTO $selectedCell = null;

    /**
     * @var Collection<int, CellDTO>
     */
    #[Locked]
    public Collection $availableMoves;

    #[Locked]
    public bool $isWhiteMove = true;

    public function boot(): void
    {
        $this->checkCoordinatesValidityService = app(CheckCoordinatesValidityService::class);
    }

    public function mount(GetInitializedBoardService $getInitializedBoardService): void
    {
        $this->availableMoves = collect();
        $this->field = $getInitializedBoardService->run();
    }

    public function render(): View
    {
        return view('livewire.chessboard.chessboard');
    }

    public function selectCell(int $x, int $y): void
    {
        /** @var ChessMoveService $chessMoveService */
        $chessMoveService = app(ChessMoveService::class, ['field' => $this->field]);

        if (!$this->checkCoordinatesValidityService->run($x, $y)) {
            $this->skipRender();
            return;
        }

        if (!$this->selectedCell) {
            $this->handleEmptySelectedCell($chessMoveService, $x, $y);
            return;
        }

        if (
            isset($this->field[$y][$x]->pieceDTO) &&
            $this->field[$y][$x]->pieceDTO->isWhite === $this->selectedCell->pieceDTO?->isWhite
        ) {
            $this->selectedCell = $this->field[$y][$x];
            $this->availableMoves = $chessMoveService->getAvailableMoves($this->selectedCell);
            return;
        }

        $fromX = $this->selectedCell->x;
        $fromY = $this->selectedCell->y;

        if (!$chessMoveService->isMoveValid($this->selectedCell, $this->field[$y][$x])) {
            $this->skipRender();
            return;
        }

        $this->field[$y][$x]->pieceDTO = $this->field[$fromY][$fromX]->pieceDTO;
        $this->field[$fromY][$fromX]->pieceDTO = null;

        $this->availableMoves = collect();
        $this->selectedCell = null;

        $this->isWhiteMove = !$this->isWhiteMove;
    }

    private function handleEmptySelectedCell(ChessMoveService $chessMoveService, int $x, int $y): void
    {
        $containsPiece = isset($this->field[$y][$x]->pieceDTO);
        $containsPieceOfOppositeSide = $this->field[$y][$x]->pieceDTO?->isWhite !== $this->isWhiteMove;

        if (!$containsPiece || $containsPieceOfOppositeSide) {
            $this->skipRender();
        } else {
            $this->selectedCell = $this->field[$y][$x];
            $this->availableMoves = $chessMoveService->getAvailableMoves($this->selectedCell);
        }
    }
}
