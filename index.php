<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heatmap Display</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Heatmap</h1>
    <div class="grid grid-flow-row grid-cols-7 gap-1">
        <?php

        require_once __DIR__ . "/vendor/autoload.php";

        use Blumilk\HeatmapBuilder\Decorators\TailwindDecorator;
        use Blumilk\HeatmapBuilder\HeatmapBuilder;
        use Blumilk\HeatmapBuilder\PeriodInterval;
        use Carbon\Carbon;
        use Carbon\CarbonPeriod;

        $jsonData = file_get_contents('data.json');
        $data = json_decode($jsonData, true);

        $now = Carbon::now();
        $interval = PeriodInterval::Daily;

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $now = Carbon::now();

        $startOfHour = $now->startOfHour();

        $heatmapBuilder = new HeatmapBuilder(
            now: $startOfHour,
            periodInterval: $interval,
            period: CarbonPeriod::create($startOfMonth, '1 day', $endOfMonth),
            decorator: new TailwindDecorator("red"),
            alignedToStartOfPeriod: true,
            alignedToEndOfPeriod: true,
            //timezone: new \Carbon\CarbonTimeZone('+2'),
        );

        $heatmap = $heatmapBuilder->build($data);

        foreach ($heatmap as $tile) {
            echo "<div class='w-12 h-12 {$tile->description} text-black rounded flex items-center justify-center border border-gray-300'></div>";
        }

        file_put_contents('heatmapOutput.json', json_encode($heatmap));
        ?>
    </div>
</div>
</body>
</html>
