<?php

declare(strict_types=1);

use Blumilk\HeatmapBuilder\Decorators\TailwindDecorator;
use Blumilk\HeatmapBuilder\HeatmapBuilder;
use Blumilk\HeatmapBuilder\Tile;
use Carbon\Carbon;
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
}
