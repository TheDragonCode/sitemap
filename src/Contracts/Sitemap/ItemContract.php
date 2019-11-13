<?php

namespace Helldar\Contracts\Sitemap;

use DateTimeInterface;
use Helldar\Sitemap\Contracts\Sitemap\GetaableContract;

interface ItemContract extends GetaableContract
{
    public function changefreq(string $frequency): self;

    public function lastmod(DateTimeInterface $date = null): self;

    public function loc(string $url): self;

    public function priority(float $value = 0.5): self;
}
