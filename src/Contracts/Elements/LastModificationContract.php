<?php

namespace Helldar\Sitemap\Contracts\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;

interface LastModificationContract
{
    public function lastmod($date = null): ItemContract;
}
