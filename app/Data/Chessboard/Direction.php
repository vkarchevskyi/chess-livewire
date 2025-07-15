<?php

declare(strict_types=1);

namespace App\Data\Chessboard;

final readonly class Direction
{
    public function __construct(public int $x, public int $y)
    {
    }

    /**
     * @return list<Direction>
     */
    public static function collect(array $coordinates): array
    {
        $instances = [];
        foreach ($coordinates as $pair) {
            $instances[] = new Direction($pair['x'], $pair['y']);
        }

        return $instances;
    }
}
