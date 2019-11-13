<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Contracts\ConfigContract;
use Illuminate\Support\Facades\Config as IlluminateConfig;

class Config implements ConfigContract
{
    static public function get(string $key, $default = null)
    {
        $key = self::key($key);

        return IlluminateConfig::get($key, $default);
    }

    static private function key(string $key): string
    {
        return 'sitemap.' . $key;
    }
}
