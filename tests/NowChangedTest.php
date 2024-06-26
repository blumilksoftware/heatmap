<?php

declare(strict_types=1);

use Blumilk\HeatmapBuilder\HeatmapBuilder;
use Blumilk\HeatmapBuilder\Tile;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use PHPUnit\Framework\TestCase;

class NowChangedTest extends TestCase
{
    public function testChangingNow(): void
    {
        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-19"),
            timezone: new CarbonTimeZone("1"),
        );
        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 2, 0, 1, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testChangingNowByMethod(): void
    {
        $builder = new HeatmapBuilder();
        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-20"),
            timezone: new CarbonTimeZone("1"),
        );

        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 2, 0, 1, 1, 0],
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
