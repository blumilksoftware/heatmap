<?php

declare(strict_types=1);

namespace Blumilk\Heatmap;

use Carbon\CarbonPeriod;

trait PeriodActions
{
    public function forLastMonth(): static
    {
        $start = $this->now->copy()->subMonth();
        $end = $this->now->copy();

        $this->period = new CarbonPeriod($start, $this->periodInterval->value, $end);

        return $this;
    }

    public function forLastYear(): static
    {
        $start = $this->now->copy()->subYear();
        $end = $this->now->copy();

        $this->period = new CarbonPeriod($start, $this->periodInterval->value, $end);

        return $this;
    }

    public function forLastDecade(): static
    {
        $start = $this->now->copy()->subDecade();
        $end = $this->now->copy();

        $this->period = new CarbonPeriod($start, $this->periodInterval->value, $end);

        return $this;
    }

    public function forNumberOfTiles(int $number): static
    {
        $start = $this->now->copy()->sub(
            $number,
            match ($this->periodInterval) {
                PeriodInterval::Hourly => "hours",
                PeriodInterval::Daily => "days",
                PeriodInterval::Weekly => "weeks",
                PeriodInterval::Monthly => "months",
            },
        );
        $end = $this->now->copy();

        $this->period = new CarbonPeriod($start, $this->periodInterval->value, $end);

        return $this;
    }
}
