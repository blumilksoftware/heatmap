<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder;

use JsonSerializable;

class Tile implements JsonSerializable
{
    public function __construct(
        public readonly string $label,
        public readonly int $count,
        public string $description = "",
    ) {}

    public function jsonSerialize(): array
    {
        return [
            "label" => $this->label,
            "count" => $this->count,
            "description" => $this->description,
        ];
    }
}
