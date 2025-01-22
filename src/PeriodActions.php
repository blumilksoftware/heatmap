<?php

declare(strict_types=1);

namespace Blumilk\Heatmap;

use Carbon\CarbonPeriod;

trait PeriodActions
{
    private const UNIT_HOURS = 'hours';
    private const UNIT_DAYS = 'days';
    private const UNIT_WEEKS = 'weeks';
    private const UNIT_MONTH = 'month';
    private const UNIT_YEAR = 'year';
    private const UNIT_DECADE = 'decade';
    private const DEFAULT_TIME_UNIT_DURATION = 1;

    /**
     * Generic method to set a period based on the starting point and the duration type.
     */
    private function setPeriodFromDuration($duration, string $unit): static
    {
        $start = $this->now->copy()->sub($duration, $unit);
        $end = $this->now->copy();
        $this->period = new CarbonPeriod($start, $this->periodInterval->value, $end);

        return $this;
    }

    public function forLastMonth(): static
    {
        return $this->setPeriodFromDuration(self::DEFAULT_TIME_UNIT_DURATION, self::UNIT_MONTH);
    }

    public function forLastYear(): static
    {
        return $this->setPeriodFromDuration(self::DEFAULT_TIME_UNIT_DURATION, self::UNIT_YEAR);
    }

    public function forLastDecade(): static
    {
        return $this->setPeriodFromDuration(self::DEFAULT_TIME_UNIT_DURATION, self::UNIT_DECADE);
    }

    public function forNumberOfTiles(int $number): static
    {
        $unit = match ($this->periodInterval) {
            PeriodInterval::Hourly => self::UNIT_HOURS,
            PeriodInterval::Daily => self::UNIT_DAYS,
            PeriodInterval::Weekly => self::UNIT_WEEKS,
            PeriodInterval::Monthly => self::UNIT_MONTH,
        };

        return $this->setPeriodFromDuration($number, $unit);
    }
}
