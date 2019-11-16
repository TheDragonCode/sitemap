<?php

namespace Helldar\Sitemap\Traits\Elements;

use DateTimeInterface;
use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Date;

trait LastModifiable
{
    protected $lastmod;

    public function lastmod($date = null): ItemContract
    {
        $this->lastmod = $date;

        return $this;
    }

    protected function getLastmodAttribute(): DateTimeInterface
    {
        return Date::parse($this->lastmod);
    }
}
