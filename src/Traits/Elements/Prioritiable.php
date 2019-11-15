<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Constants\Priority as PriorityConst;
use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Priority;

trait Prioritiable
{
    protected $priority = PriorityConst::DEFAULT;

    public function priority(float $value = 0.5): ItemContract
    {
        $this->priority = Priority::get($value);

        return $this;
    }
}
