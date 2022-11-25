<?php

declare(strict_types=1);

use Blumilk\HeatmapBuilder\HeatmapBuilder;
use Blumilk\HeatmapBuilder\Tile;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ArrayAccessItemsTest extends TestCase
{
    public function testBasicArrayAccessItems(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-18"));
        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testArrayAccessItemsWithIndexChanged(): void
    {
        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            arrayAccessIndex: "updated_at",
        );
        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    public function testArrayAccessItemsWithIndexChangedByMethod(): void
    {
        $builder = new HeatmapBuilder(now: Carbon::parse("2022-11-18"));
        $builder->changeArrayAccessIndex("updated_at");

        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 0],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    protected function getData(): array
    {
        return [
            ["created_at" => "2022-11-01 00:00:00", "updated_at" => "2022-11-01 00:00:00"],
            ["created_at" => "2022-11-03 00:00:00", "updated_at" => "2022-11-03 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00", "updated_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-16 00:00:00", "updated_at" => "2022-11-16 00:00:00"],
            ["created_at" => "2022-11-18 00:00:00", "updated_at" => "2022-11-19 00:00:00"],
            ["created_at" => "2022-11-19 00:00:00", "updated_at" => "2022-11-19 00:00:00"],
        ];
    }
}
