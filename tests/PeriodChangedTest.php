<?php

declare(strict_types=1);

use Blumilk\Heatmap\HeatmapBuilder;
use Blumilk\Heatmap\Tile;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\TestCase;

class PeriodChangedTest extends TestCase
{
    public function testChangingPeriod(): void
    {
        $start = Carbon::parse("2022-11-01");
        $end = Carbon::parse("2022-11-11");
        $builder = new HeatmapBuilder(period: new CarbonPeriod($start, "1 day", $end));

        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testChangingPeriodByMethod(): void
    {
        $start = Carbon::parse("2022-11-01");
        $end = Carbon::parse("2022-11-11");

        $builder = new HeatmapBuilder();
        $builder->changePeriod(new CarbonPeriod($start, "1 day", $end));

        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    protected function getData(): array
    {
        return [
            ["created_at" => "2022-11-01 00:00:00"],
            ["created_at" => "2022-11-03 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-18 00:00:00"],
            ["created_at" => "2022-11-19 00:00:00"],
        ];
    }
}
