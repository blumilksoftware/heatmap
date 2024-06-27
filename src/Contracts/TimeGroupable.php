<?php

declare(strict_types=1);

namespace Blumilk\Heatmap\Contracts;

interface TimeGroupable
{
    public function getTimeGroupableIndicator(): string;
}
