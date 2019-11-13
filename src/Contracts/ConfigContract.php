<?php

namespace Helldar\Sitemap\Contracts;

interface ConfigContract
{
    public static function get(string $key, $default = null);
}
