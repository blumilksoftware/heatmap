<?php

declare(strict_types=1);

use Blumilk\HeatmapBuilder\HeatmapBuilder;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PeriodAligningTest extends TestCase
{
    public function testAligningPeriodForDailyHeatmap(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-19 00:00:00"));
        $builder->forLastMonth()->alignedToStartOfPeriod();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 33),
            actual: $result,
        );
    }

    public function testAligningPeriodForDailyHeatmapFromBothSides(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-19 00:00:00"));
        $builder->forLastMonth()->alignedToStartOfPeriod()->alignedToEndOfPeriod();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 34),
            actual: $result,
        );
    }

    public function testAligningPeriodForNumberOfTiles(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-19 00:00:00"));
        $builder->forNumberOfTiles(4)->alignedToStartOfPeriod();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 5),
            actual: $result,
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
