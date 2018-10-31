<?php

namespace Helldar\Sitemap\Interfaces;

use Helldar\Sitemap\Services\Items\MakeImages;
use Helldar\Sitemap\Services\Items\MakeItem;
use Illuminate\Database\Eloquent\Builder;

interface SitemapInterface
{
    public function makeItem(): MakeItem;

    public function makeImages(): MakeImages;

    public function builders(Builder ...$builders);

    public function manual(array ...$items);

    public function images(array ...$images);
}
