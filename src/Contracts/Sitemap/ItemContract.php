<?php

namespace Helldar\Contracts\Sitemap;

use DateTimeInterface;
use Helldar\Sitemap\Contracts\Sitemap\GetaableContract;

interface ItemContract extends GetaableContract
{
    public function changefreq(string $frequency): ItemContract;

    public function lastmod(DateTimeInterface $date = null): ItemContract;

    public function loc(string $url): ItemContract;

    public function priority(float $value = 0.5): ItemContract;
}
