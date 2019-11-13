<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Constants\Priority;
use Helldar\Sitemap\Contracts\PriorityContract;

class Priorities implements PriorityContract
{
    public static function get(float $priority = 0.5): float
    {
        return $priority >= 0.1 && $priority <= 1
            ? $priority
            : Priority::DEFAULT;
    }
}
