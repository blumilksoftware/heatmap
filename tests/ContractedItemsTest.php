<?php

declare(strict_types=1);

use Blumilk\HeatmapBuilder\Contracts\TimeGroupable;
use Blumilk\HeatmapBuilder\HeatmapBuilder;
use Blumilk\HeatmapBuilder\Tile;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use PHPUnit\Framework\TestCase;

class ContractedItemsTest extends TestCase
{
    public function testBasicContractedItems(): void
    {
        $builder = new HeatmapBuilder(
            now: Carbon::parse("2022-11-18"),
            timezone: new CarbonTimeZone("1"),
        );
        $result = $builder->build($this->getData());

        $this->assertSame(
            expected: [0, 0, 0, 0, 0, 2, 0, 1],
            actual: array_map(fn(Tile $item): int => $item->count, $result),
        );
    }

    protected static function getTimeGroupableElement(string $date): TimeGroupable
    {
        return new class($date) implements TimeGroupable {
            public function __construct(
                protected string $date,
            ) {}

            public function getTimeGroupableIndicator(): string
            {
                return $this->date;
            }
        };
    }

    protected function getData(): array
    {
        return [
            static::getTimeGroupableElement("2022-11-01 00:00:00"),
            static::getTimeGroupableElement("2022-11-03 00:00:00"),
            static::getTimeGroupableElement("2022-11-16 00:00:00"),
            static::getTimeGroupableElement("2022-11-16 00:00:00"),
            static::getTimeGroupableElement("2022-11-18 00:00:00"),
            static::getTimeGroupableElement("2022-11-19 00:00:00"),
        ];
    }
}
