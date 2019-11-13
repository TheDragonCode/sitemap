<?php

namespace Helldar\Sitemap\Traits\Processes;

use function compact;
use Helldar\Core\Xml\Facades\Xml;
use Helldar\Core\Xml\Helpers\Str;
use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Make\Item;
use Helldar\Sitemap\Services\Sitemap;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Config;

trait ManualProcess
{
    /** @var Xml */
    protected $xml;

    /** @var array */
    protected $manuals = [];

    public function makeItem(): Item
    {
        return new Item;
    }

    /**
     * Send a set of manually created items for processing.
     *
     * @param array|Item $items
     *
     * @return Sitemap
     */
    public function manual(...$items): self
    {
        $this->manuals = (array) $items;

        return $this;
    }

    /**
     * Reading the configuration of the manually transferred items and creating a link for saving to the sitemap.
     *
     * @param array $item
     */
    protected function processManuals(array $item = [])
    {
        $item = new Collection($item);

        $loc        = Str::e($item->get('loc', Config::get('app.url')));
        $changefreq = Variables::correctFrequency($item->get('changefreq', Config::get('sitemap.frequency', 'daily')));
        $lastmod    = Variables::getDate($item->get('lastmod'))->toAtomString();
        $priority   = Variables::correctPriority($item->get('priority', Config::get('sitemap.priority', 0.5)));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'), 'url');
    }
}
