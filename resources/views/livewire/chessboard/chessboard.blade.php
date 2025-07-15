@use('App\Data\Chessboard\Cell;')
@use('App\Enums\Chessboard\PieceType')
@use('Illuminate\Support\Collection')

@props([
    'field',
    'isWhiteMove',
    'selectedCell',
    'availableMoves',
    'isWhiteMove'
])

@php
    /** @var Cell[][] $field */
    /** @var bool $isWhiteMove */
    /** @var ?Cell $selectedCell */
    /** @var Collection<int, Cell> $availableMoves */
    /** @var bool $isWhiteMove */
@endphp

<div class="flex justify-between">
    <div id="chessboard" class="relative">
        <div class="flex justify-center items-center max-w-lg">
            @for($x = 0; $x < 8; $x++)
                <div class="w-16 text-center" wire:key="chessboard-column-identifier-{{ $x }}">
                    {{ chr(ord('A') + $x) }}
                </div>
            @endfor
        </div>

        @for($y = 0; $y < 8; $y++)
            <div class="flex justify-start" wire:key="chessboard-row-{{ $y }}">
                <div class="flex max-w-lg">
                    @for($x = 0; $x < 8; $x++)
                        <div
                            wire:click="selectCell({{ $x }}, {{ $y }})"
                            wire:key="chessboard-cell-{{ $y }}-{{ $x }}"
                        >
                            @php
                                $cellKey = sprintf(
                                    'chess-cell-%d-%d-%d-%s-%s-%s',
                                    $y,
                                    $x,
                                    $field[$y][$x]->piece?->isWhite,
                                    $field[$y][$x]->piece?->type->value,
                                    $selectedCell?->x,
                                    $selectedCell?->y
                                );

                                $isAvailableForMove = $availableMoves
                                    ->filter(fn (Cell $cellDTO) => $cellDTO->x === $x && $cellDTO->y === $y)
                                    ->count() >= 1;
                            @endphp

                            <livewire:chessboard.cell
                                :cell="$field[$y][$x]"
                                :key="$cellKey"
                                :isSelected="$selectedCell?->x === $x && $selectedCell?->y === $y"
                                :isAvailableForMove="$isAvailableForMove"
                            />
                        </div>
                    @endfor

                    <p
                        class="w-8 ml-2 self-center text-center"
                        wire:key="chessboard-row-identifier-{{ $y }}"
                    >
                        {{ $y + 1 }}
                    </p>
                </div>
            </div>
        @endfor

        <div
            class="absolute top-1/3 left-1/5 bg-linear-to-r from-cyan-500 to-blue-500 bg-gradient-to-t p-6 rounded-xl space-y-3"
            x-data="{ x: -1, y: -1, show: false }"
            x-cloak
            x-show="show"
            x-on:click.outside="show = false;"
            x-on:show-promotion-modal.window="x = $event.detail.x; y = $event.detail.y; show = true;"
            wire:key="promotion-modal"
        >
            <h4 class="text-center">Choose your piece type</h4>

            <div class="flex gap-4">
                @foreach([PieceType::ROOK, PieceType::BISHOP, PieceType::QUEEN] as $type)
                    @php /** @var PieceType $type */ @endphp
                    <button
                        class="border border-black rounded-xl p-2 text-black hover:bg-black hover:text-cyan-500 transition-colors"
                        x-on:click="$wire.selectCell(x, y, '{{ $type->value }}'); show = false; x = -1; y = -1;"
                        wire:key="promotion-modal-piece-type-{{ $type->value }}"
                    >
                        {{ $type->getLabel() }} {{ $type->getView($isWhiteMove) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center gap-y-4">
        @if(isset($isWhiteWin))
            <h1 class="text-2xl font-bold">{{ $isWhiteWin ? 'White' : 'Black' }} is winning!</h1>

            <button class="px-2 py-2 bg-blue-600 text-white font-bold" wire:click.prevent="reinitBoard">
                Restart
            </button>
        @endif
    </div>
</div>
