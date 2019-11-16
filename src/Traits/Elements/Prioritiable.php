<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Constants\Priority as Prior;
use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Priority;

trait Prioritiable
{
    protected $priority = Prior::DEFAULT;

    public function priority(float $value = 0.5): ItemContract
    {
        $this->priority = $value;

        return $this;
    }

    protected function getPriorityAttribute(): float
    {
        return Priority::get($this->priority);
    }
}
