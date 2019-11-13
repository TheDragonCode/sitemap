<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Contracts\ConfigContract;
use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config implements ConfigContract
{
    public static function get(string $key, $default = null)
    {
        $key    = static::key($key);
        $config = static::resolve();

        return $config::get($key, $default);
    }

    private static function key(string $key): string
    {
        return 'sitemap.' . $key;
    }

    /**
     * @return IlluminateConfig|string
     */
    private static function resolve()
    {
        return IlluminateConfig::class;
    }
}
