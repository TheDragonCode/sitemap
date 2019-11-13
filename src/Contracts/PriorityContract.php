<?php

namespace Helldar\Sitemap\Contracts;

interface PriorityContract
{
    static public function get(float $priority = 0.5): float;
}
