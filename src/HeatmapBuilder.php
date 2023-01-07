<?php

declare(strict_types=1);

namespace Blumilk\HeatmapBuilder;

use ArrayAccess;
use Blumilk\HeatmapBuilder\Contracts\Decorator;
use Blumilk\HeatmapBuilder\Contracts\TimeGroupable;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HeatmapBuilder
{
    use PeriodActions;

    protected const DEFAULT_ARRAY_ACCESS_INDEX = "created_at";

    public function __construct(
        protected Carbon $now = new Carbon(),
        protected PeriodInterval $periodInterval = PeriodInterval::Daily,
        protected ?CarbonPeriod $period = null,
        protected string $arrayAccessIndex = self::DEFAULT_ARRAY_ACCESS_INDEX,
        protected ?Decorator $decorator = null,
        protected bool $alignedToStartOfPeriod = false,
        protected bool $alignedToEndOfPeriod = false,
    ) {}

    /**
     * @param iterable<array|ArrayAccess|TimeGroupable> $data
     */
    public function build(iterable $data): array
    {
        $data = $this->mapDataToCarbonElements($data);
        $period = $this->getPeriod();

        $bucket = [];
        foreach ($period as $date) {
            $bucket[] = new Tile(
                label: match ($this->periodInterval) {
                    PeriodInterval::Monthly => $date->format("Y-m"),
                    PeriodInterval::Weekly => $date->format("Y:W"),
                    PeriodInterval::Daily => $date->format("Y-m-d"),
                },
                count: count(
                    array_filter($data, fn(Carbon $item): bool => match ($this->periodInterval) {
                        PeriodInterval::Monthly => $item->isSameMonth($date),
                        PeriodInterval::Weekly => $item->isSameWeek($date),
                        PeriodInterval::Daily => $item->isSameDay($date),
                    }),
                ),
                inFuture: $date->isFuture(),
            );
        }

        if ($this->decorator !== null) {
            $bucket = $this->decorator->decorate($bucket);
        }

        return $bucket;
    }

    public function changeArrayAccessIndex(string $arrayAccessIndex): static
    {
        $this->arrayAccessIndex = $arrayAccessIndex;
        return $this;
    }

    public function changeNow(Carbon $now): static
    {
        $this->now = $now;
        return $this;
    }

    public function changePeriod(CarbonPeriod $period): static
    {
        $this->period = $period;
        return $this;
    }

    public function changePeriodInterval(PeriodInterval $interval): static
    {
        $this->periodInterval = $interval;
        return $this;
    }

    public function alignedToStartOfPeriod(): static
    {
        $this->alignedToStartOfPeriod = true;
        return $this;
    }

    public function alignedToEndOfPeriod(): static
    {
        $this->alignedToEndOfPeriod = true;
        return $this;
    }

    /**
     * @param iterable<array|ArrayAccess|TimeGroupable> $data
     */
    protected function mapDataToCarbonElements(iterable $data): array
    {
        return array_map(
            callback: fn(array|ArrayAccess|TimeGroupable $item): Carbon => $this->mapToCarbon($item),
            array: [...$data],
        );
    }

    protected function mapToCarbon(array|ArrayAccess|TimeGroupable $item): Carbon
    {
        $date = $item instanceof TimeGroupable
            ? Carbon::parse($item->getTimeGroupableIndicator())
            : Carbon::parse($item[$this->arrayAccessIndex]);

        return match ($this->periodInterval) {
            PeriodInterval::Monthly => $date->startOfMonth(),
            PeriodInterval::Weekly => $date->startOfWeek(),
            PeriodInterval::Daily => $date->startOfDay(),
        };
    }

    protected function getPeriod(): CarbonPeriod
    {
        $period = $this->period === null
            ? new CarbonPeriod(
                match ($this->periodInterval) {
                    PeriodInterval::Monthly => $this->now->copy()->subYear(),
                    PeriodInterval::Weekly => $this->now->copy()->subMonth(),
                    PeriodInterval::Daily => $this->now->copy()->subWeek(),
                },
                $this->periodInterval->value,
                $this->now->copy(),
            )
            : $this->period;

        if ($this->alignedToStartOfPeriod) {
            $from = $period->getStartDate()->copy();
            $from = match ($this->periodInterval) {
                PeriodInterval::Monthly => $from->startOfYear(),
                PeriodInterval::Weekly => $from->startOfMonth(),
                PeriodInterval::Daily => $from->startOfWeek(),
            };

            $period->setStartDate($from);
        }

        if ($this->alignedToEndOfPeriod) {
            $to = $period->getEndDate()->copy();
            $to = match ($this->periodInterval) {
                PeriodInterval::Monthly => $to->endOfYear(),
                PeriodInterval::Weekly => $to->endOfMonth(),
                PeriodInterval::Daily => $to->endOfWeek(),
            };

            $period->setEndDate($to);
        }

        return $period;
    }
}
