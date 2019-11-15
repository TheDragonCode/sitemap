<?php

namespace Helldar\Sitemap\Traits\Make;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Make\Item;

trait MakeItem
{
    public function item(): ItemContract
    {
        return new Item();
    }
}
