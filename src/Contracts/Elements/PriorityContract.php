<?php

namespace Helldar\Sitemap\Contracts\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;

interface PriorityContract
{
    public function priority(float $value = 0.5): ItemContract;
}
