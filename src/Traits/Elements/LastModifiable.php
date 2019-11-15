<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Date;

trait LastModifiable
{
    protected $lastmod;

    public function lastmod($date = null): ItemContract
    {
        $this->lastmod = Date::parse($date);

        return $this;
    }
}
