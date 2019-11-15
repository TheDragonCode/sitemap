<?php

namespace Helldar\Sitemap\Contracts\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;

interface LocationContract
{
    public function loc(string $url): ItemContract;
}
