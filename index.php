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

        $now = Carbon::now();
        $interval = PeriodInterval::Daily;
        $data = [
            ["created_at" => "2024-02-31 00:00:00"],
            ["created_at" => "2024-05-31 00:00:00"],
            ["created_at" => "2024-05-31 00:00:00"],
            ["created_at" => "2024-06-01 00:00:00"],
            ["created_at" => "2024-06-01 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-04 00:00:00"],
            ["created_at" => "2024-06-08 00:00:00"],
            ["created_at" => "2024-06-09 00:00:00"],
            ["created_at" => "2024-06-11 00:00:00"],
            ["created_at" => "2024-06-12 00:00:00"],
            ["created_at" => "2024-06-13 00:00:00"],
            ["created_at" => "2024-06-14 00:00:00"],
        ];

        $startOfMonth = $now->copy()->startOfMonth();

        $endOfMonth = $now->copy()->endOfMonth();

        $heatmapBuilder = new HeatmapBuilder(
            now: $now,
            periodInterval: $interval,
            period: CarbonPeriod::create($startOfMonth, $endOfMonth),
            decorator: new TailwindDecorator("green"),
            alignedToStartOfPeriod: true,
            alignedToEndOfPeriod: true,

        );

        $heatmap = $heatmapBuilder->build($data);

        foreach ($heatmap as $tile) {
            echo "<div class='w-6 h-6 {$tile->description} text-black rounded flex items-center justify-center border-radius: 0.25rem border-width: 1px; border-color: rgb(209, 213, 219)'></div>";
            }
        ?>
    </div>
</div>
</body>
</html>
