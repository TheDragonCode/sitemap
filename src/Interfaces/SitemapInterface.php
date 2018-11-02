<?php

namespace Helldar\Sitemap\Interfaces;

use Helldar\Sitemap\Services\Make\Images;
use Helldar\Sitemap\Services\Make\Item;
use Illuminate\Database\Eloquent\Builder;

interface SitemapInterface
{
    public function makeItem(): Item;

    public function makeImages(): Images;

    public function builders(Builder ...$builders);

    public function manual(array ...$items);

    public function images(array ...$images);
}
