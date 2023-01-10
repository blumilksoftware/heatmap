<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder\Decorators;

use Blumilk\HeatmapBuilder\Contracts\Decorator;
use Blumilk\HeatmapBuilder\Tile;

class OpacityDecorator implements Decorator
{
    public function __construct(
        public readonly string $color,
    ) {}

    public function decorate(array $bucket): array
    {
        $max = max(0, ...array_map(fn(Tile $tile): int => $tile->count, $bucket));

        /** @var Tile $tile */
        foreach ($bucket as $tile) {
            $tile->description = match (true) {
                $tile->count === 0 => "background: White",
                $tile->count === $max => "background: {$this->color}; opacity: .9;",
                $tile->count >= $max * .9 => "background: {$this->color}; opacity: .8;",
                $tile->count >= $max * .8 => "background: {$this->color}; opacity: .7;",
                $tile->count >= $max * .7 => "background: {$this->color}; opacity: .6;",
                $tile->count >= $max * .6 => "background: {$this->color}; opacity: .5;",
                $tile->count >= $max * .5 => "background: {$this->color}; opacity: .4;",
                $tile->count >= $max * .4 => "background: {$this->color}; opacity: .3;",
                $tile->count >= $max * .3 => "background: {$this->color}; opacity: .2;",
                $tile->count >= $max * .2 => "background: {$this->color}; opacity: .1;",
                default => "background: {$this->color}; opacity: .05;",
            };

            if ($tile->inFuture) {
                $tile->description = " opacity: .25;";
            }
        }

        return $bucket;
    }
}
