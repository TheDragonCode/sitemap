<?php

namespace Helldar\Sitemap\Contracts\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;

interface FrequencyContract
{
    public function changefreq(string $frequency): ItemContract;
}
