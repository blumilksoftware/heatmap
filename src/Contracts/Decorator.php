<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder\Contracts;

use Blumilk\HeatmapBuilder\Tile;

interface Decorator
{
    /**
     * @param array<Tile> $bucket
     * @return array<Tile>
     */
    public function decorate(array $bucket): array;
}
