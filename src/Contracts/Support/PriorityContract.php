<?php

namespace Helldar\Sitemap\Contracts\Support;

interface PriorityContract
{
    public static function get(float $value = 0.5): float;
}
