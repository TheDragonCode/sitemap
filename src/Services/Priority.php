<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Constants\Priority as PriorityConstant;
use Helldar\Sitemap\Contracts\PriorityContract;

class Priority implements PriorityContract
{
    public static function get(float $value = 0.5): float
    {
        return $value >= 0.1 && $value <= 1
            ? $value
            : PriorityConstant::DEFAULT;

    }
}
