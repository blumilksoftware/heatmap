<?php

declare(strict_types=1);

use Blumilk\Heatmap\HeatmapBuilder;
use Blumilk\Heatmap\PeriodInterval;
use Blumilk\Heatmap\Tile;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PeriodIntervalTest extends TestCase
{
    public function testChangingPeriodForMonth(): void
    {
        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-19"),
            periodInterval: PeriodInterval::Monthly,
        );
        $builder->forLastYear();

        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 1, 0, 0, 2, 0, 0, 0, 0, 1, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    protected function getData(): array
    {
        return [
            ["created_at" => "2021-01-01 00:00:00"],
            ["created_at" => "2022-01-01 00:00:00"],
            ["created_at" => "2022-04-16 00:00:00"],
            ["created_at" => "2022-04-16 00:00:00"],
            ["created_at" => "2022-09-18 00:00:00"],
            ["created_at" => "2022-11-19 00:00:00"],
        ];
    }
}
