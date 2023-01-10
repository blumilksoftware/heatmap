<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder\Decorators;

use Blumilk\HeatmapBuilder\Contracts\Decorator;
use Blumilk\HeatmapBuilder\Tile;

class TailwindDecorator implements Decorator
{
    public function __construct(
        public readonly string $theme,
    ) {}

    public function decorate(array $bucket): array
    {
        $max = max(0, ...array_map(fn(Tile $tile): int => $tile->count, $bucket));

        /** @var Tile $tile */
        foreach ($bucket as $tile) {
            $tile->description = match (true) {
                $tile->count === 0 => "bg-white",
                $tile->count === $max => "bg-{$this->theme}-900",
                $tile->count >= $max * .9 => "bg-{$this->theme}-800",
                $tile->count >= $max * .8 => "bg-{$this->theme}-700",
                $tile->count >= $max * .7 => "bg-{$this->theme}-600",
                $tile->count >= $max * .6 => "bg-{$this->theme}-500",
                $tile->count >= $max * .5 => "bg-{$this->theme}-400",
                $tile->count >= $max * .4 => "bg-{$this->theme}-300",
                $tile->count >= $max * .3 => "bg-{$this->theme}-200",
                $tile->count >= $max * .2 => "bg-{$this->theme}-100",
                default => "bg-{$this->theme}-50",
            };

            if ($tile->inFuture) {
                $tile->description = " opacity-25";
            }
        }

        return $bucket;
    }
}
