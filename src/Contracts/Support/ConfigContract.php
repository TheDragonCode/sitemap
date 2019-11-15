<?php

namespace Helldar\Sitemap\Contracts\Support;

interface ConfigContract
{
    public static function get(string $key, $default = null);
}
