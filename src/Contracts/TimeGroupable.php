<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder\Contracts;

interface TimeGroupable
{
    public function getTimeGroupableIndicator(): string;
}
