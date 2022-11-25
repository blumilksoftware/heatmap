## About
Making a heatmap from custom datasets should be extremely easy. We are here to help you with that.

What's a heatmap? It is a data visualization technique that shows magnitude of a phenomenon as color in two dimensions<sup>[[1](https://en.wikipedia.org/wiki/Heat_map)]</sup>, and you can know it from GitHub user profile pages.

### Installation
The Heatmap is distributed as Composer library via Packagist. To add it to your project, just run:
```
composer require blumilksoftware/heatmap
```

### Usage
You can use any set of data you want. By default, dates will be taken from `"created_at"` key of arrays or objects implementing `\ArrayAccess` interface. Builder accepts also objects implementing `\Blumilk\HeatmapBuilder\Contracts\TimeGroupable` contract with mandatory `getTimeGroupableIndicator()` method returning a string with proper date:
```php
$data = [
    ["created_at" => "2022-11-01 00:00:00", /** (...) */],
    ["created_at" => "2022-11-03 00:00:00", /** (...) */],
    ["created_at" => "2022-11-16 00:00:00", /** (...) */],
    ["created_at" => "2022-11-16 00:00:00", /** (...) */],
    ["created_at" => "2022-11-18 00:00:00", /** (...) */],
    ["created_at" => "2022-11-19 00:00:00", /** (...) */],
];
```

Then create an instance of `\Blumilk\HeatmapBuilder\HeatmapBuilder`. By default, it will be working on day-based periods for last week from the moment you are calling it:
```php
$builder = new HeatmapBuilder();
$result = $builder->build($data);
```

Method `build()` returns array of tiles that can be serialized into JSON with simple `json_encode()` function or any other serializer: 
```json
[
  {
    "label": "2022-11-16",
    "count": 2,
    "description": ""
  },
  {
    "label": "2022-11-17",
    "count": 0,
    "description": ""
  },
  {
    "label": "2022-11-18",
    "count": 1,
    "description": ""
  },
  {
    "label": "2022-11-19",
    "count": 1,
    "description": ""
  },
  {
    "label": "2022-11-20",
    "count": 0,
    "description": ""
  },
  {
    "label": "2022-11-21",
    "count": 0,
    "description": ""
  },
  {
    "label": "2022-11-22",
    "count": 0,
    "description": ""
  },
  {
    "label": "2022-11-23",
    "count": 0,
    "description": ""
  }
]
```
