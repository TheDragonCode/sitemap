<?php

namespace Helldar\Sitemap\Traits;

use Helldar\Contracts\Sitemap\ItemContract;
use Helldar\Contracts\Sitemap\SitemapContract;
use Helldar\Sitemap\Services\Make\Item;

trait ManualsProcess
{
    protected $manuals = [];

    public function item(): ItemContract
    {
        return new Item();
    }

    public function manual(ItemContract ...$items): SitemapContract
    {
        $this->manuals = (array) $items;

        return $this;
    }

    protected function processManuals(ItemContract $item)
    {

    }
}
