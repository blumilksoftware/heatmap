<?php

declare(strict_types=1);

namespace Blumilk\Heatmap\Contracts;

use Blumilk\Heatmap\Tile;

interface Decorator
{
    /**
     * @param array<Tile> $bucket
     * @return array<Tile>
     */
    public function decorate(array $bucket): array;
}
