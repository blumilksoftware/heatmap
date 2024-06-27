<?php

declare(strict_types=1);

namespace Blumilk\Heatmap;

enum PeriodInterval: string
{
    case Hourly = "1 hour";
    case Daily = "1 day";
    case Weekly = "1 week";
    case Monthly = "1 month";
}
