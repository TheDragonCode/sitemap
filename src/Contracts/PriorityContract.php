<?php

namespace Helldar\Sitemap\Contracts;

interface PriorityContract
{
    public static function get(float $priority = 0.5): float;
}
