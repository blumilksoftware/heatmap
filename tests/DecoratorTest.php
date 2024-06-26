<?php

declare(strict_types=1);

use Blumilk\Heatmap\Decorators\TailwindDecorator;
use Blumilk\Heatmap\HeatmapBuilder;
use Blumilk\Heatmap\Tile;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonTimeZone;
use PHPUnit\Framework\TestCase;

class DecoratorTest extends TestCase
{
    public function testBasicArrayAccessItems(): void
    {
        $data = [
            ["created_at" => "2022-11-13 00:00:00"],
            ["created_at" => "2022-11-13 00:00:00"],
            ["created_at" => "2022-11-14 00:00:00"],
            ["created_at" => "2022-11-14 00:00:00"],
            ["created_at" => "2022-11-14 00:00:00"],
            ["created_at" => "2022-11-14 00:00:00"],
            ["created_at" => "2022-11-14 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-18 00:00:00"],
        ];

        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            decorator: new TailwindDecorator("green"),
            timezone: new CarbonTimeZone("1"),
        );

        $result = $builder->build($data);

        $this->assertSame(
            expected: [0, 0, 2, 5, 0, 7, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );

        $defaultClasses = "size-4 border border-gray-300 rounded";

        $this->assertSame(
            expected: array_map(
                fn(string $class): string => "$defaultClasses $class",
                ["bg-white", "bg-white", "bg-green-100", "bg-green-600", "bg-white", "bg-green-900", "bg-white", "bg-green-50"],
            ),
            actual: array_map(fn(Tile $item): string => $item->description, $result),
        );
    }

    public function testTodayAndFuture(): void
    {
        $data = [];
        $now = Carbon::now();

        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                "created_at" => $now->copy()->addDays($i)->format("Y-m-d H:i:s"),
            ];
        }

        $builder = new HeatmapBuilder(
            now: $now,
            period: CarbonPeriod::create(Carbon::create($now->year, $now->month, $now->day), Carbon::create($now->year, $now->month, $now->day)->addDay(4)),
            decorator: new TailwindDecorator("green"),
            timezone: new CarbonTimeZone("1"),
        );

        $result = $builder->build($data);

        $this->assertSame(
            expected: [1, 0, 0, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->isToday ? 1 : 0, $result),
        );

        $this->assertSame(
            expected: [0, 1, 1, 1, 1],
            actual: array_map(fn(Tile $item): int => $item->inFuture ? 1 : 0, $result),
        );
    }
}
