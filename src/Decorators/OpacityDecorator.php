<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder\Decorators;

use Blumilk\HeatmapBuilder\Contracts\Decorator;
use Blumilk\HeatmapBuilder\Tile;

class OpacityDecorator implements Decorator
{
    public function __construct(
        public readonly string $color,
        public readonly string $futureTileClass = "opacity: .25",
        public readonly string $todayTileClass = "border-color: rgb(156, 163, 175)",
        public readonly string $defaultSizeClass = "width: 1rem; height: 1rem",
        public readonly string $defaultBorderClass = "border-width: 1px; border-color: rgb(209, 213, 219)",
        public readonly string $defaultRoundedClass = "border-radius: 0.25rem",
    ) {}

    public function decorate(array $bucket): array
    {
        $max = max(0, ...array_map(fn(Tile $tile): int => $tile->count, $bucket));

        /** @var Tile $tile */
        foreach ($bucket as $tile) {
            $description = $this->getDefaultAttributes();

            $description[] = match (true) {
                $tile->count === 0 => "background: White",
                $tile->count === $max => "background: {$this->color};",
                $tile->count >= $max * .9 => "background: {$this->color}; filter: brightness(90%);",
                $tile->count >= $max * .8 => "background: {$this->color}; filter: brightness(80%);",
                $tile->count >= $max * .7 => "background: {$this->color}; filter: brightness(70%);",
                $tile->count >= $max * .6 => "background: {$this->color}; filter: brightness(60%);",
                $tile->count >= $max * .5 => "background: {$this->color}; filter: brightness(50%);",
                $tile->count >= $max * .4 => "background: {$this->color}; filter: brightness(40%);",
                $tile->count >= $max * .3 => "background: {$this->color}; filter: brightness(30%);",
                $tile->count >= $max * .2 => "background: {$this->color}; filter: brightness(20%);",
                default => "background: {$this->color}; filter: brightness(10%);",
            };

            if ($tile->inFuture) {
                $description[] = $this->futureTileClass;
            }

            if ($tile->isToday) {
                $description[] = $this->todayTileClass;
            }

            $tile->description = implode("; ", $description);
        }

        return $bucket;
    }

    protected function getDefaultAttributes(): array
    {
        return [$this->defaultSizeClass, $this->defaultBorderClass, $this->defaultRoundedClass];
    }
}
