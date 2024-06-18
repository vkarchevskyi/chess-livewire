@use('App\Enums\Chessboard\PieceType')

@props([
    'pieceDTO'
])

@php
    /** @var \App\DTOs\Chessboard\PieceDTO $pieceDTO */
@endphp

<div class="text-6xl">
    <p>
        @php
            $pieceChar = match ($pieceDTO->pieceType) {
                PieceType::PAWN => $pieceDTO->isWhite ? '♙' : '♟︎',
                PieceType::BISHOP => $pieceDTO->isWhite ? '♗' : '♝',
                PieceType::KNIGHT => $pieceDTO->isWhite ? '♘' : '♞',
                PieceType::ROOK => $pieceDTO->isWhite ? '♖' : '♜',
                PieceType::QUEEN => $pieceDTO->isWhite ? '♕' : '♛',
                PieceType::KING => $pieceDTO->isWhite ? '♔' : '♚',
            }
        @endphp

        {{ $pieceChar }}
    </p>
</div>
