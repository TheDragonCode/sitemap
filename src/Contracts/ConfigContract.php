<?php

namespace Helldar\Sitemap\Contracts;

interface ConfigContract
{
    static public function get(string $key, $default = null);
}
