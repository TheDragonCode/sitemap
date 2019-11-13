<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Contracts\RouteContract;
use Illuminate\Support\Arr;

class Route implements RouteContract
{
    private static $result = [];

    public static function parameters($item, array $fields = []): array
    {
        foreach ($fields as $key => $value) {
            static::process($item, $key, $value);
        }

        return static::getResult();
    }

    private static function process($item, $key, $value)
    {
        $key   = is_numeric($key) ? $value : $key;
        $value = Arr::get($item, $value, false);

        if ($value) {
            Arr::set(static::$result, $key, $value);
        }
    }

    private static function getResult(): array
    {
        return static::$result;
    }
}
