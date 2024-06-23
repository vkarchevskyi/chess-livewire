<?php

declare(strict_types=1);

namespace App\Services\Chessboard;

class CheckCoordinatesValidityService
{
    public function run(int $x, int $y): bool
    {
        return $x >= 0 && $x < 8 && $y >= 0 && $y < 8;
    }
}
