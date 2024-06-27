<?php

declare(strict_types=1);

use Blumilk\Heatmap\HeatmapBuilder;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PeriodActionsTest extends TestCase
{
    public function testChangingPeriodForMonth(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-19 00:00:00"));
        $builder->forLastMonth();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 31),
            actual: $result,
        );
    }

    public function testChangingPeriodForShorterMonth(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-03-01 00:00:00"));
        $builder->forLastMonth();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 28),
            actual: $result,
        );
    }

    public function testChangingPeriodForYear(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-19 00:00:00"));
        $builder->forLastYear();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 365),
            actual: $result,
        );
    }

    public function testChangingPeriodForLeapYear(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2020-05-01 00:00:00"));
        $builder->forLastYear();

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 366),
            actual: $result,
        );
    }

    public function testChangingPeriodForNumberOfTiles(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-03-01 00:00:00"));
        $builder->forNumberOfTiles(100);

        $result = $builder->build($this->getData());

        $this->assertSameSize(
            expected: range(0, 100),
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
