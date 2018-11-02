<?php

namespace Helldar\Sitemap\Traits\Processes;

use Helldar\Sitemap\Helpers\Variables;
use Helldar\Sitemap\Services\Make\Item;
use Illuminate\Support\Facades\Config;

trait ManualProcess
{
    /** @var \Helldar\Sitemap\Services\Xml */
    protected $xml;

    /** @var array */
    protected $manuals = [];

    public function makeItem(): Item
    {
        return new Item();
    }

    /**
     * Send a set of manually created items for processing.
     *
     * @param array $items
     *
     * @return \Helldar\Sitemap\Services\Sitemap
     */
    public function manual(array ...$items): self
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
        $item = collect($item);

        $loc        = $this->e($item->get('loc', Config::get('app.url')));
        $changefreq = Variables::correctFrequency($item->get('changefreq', Config::get('sitemap.frequency', 'daily')));
        $lastmod    = Variables::getDate($item->get('lastmod'))->toAtomString();
        $priority   = Variables::correctPriority($item->get('priority', Config::get('sitemap.priority', 0.5)));

        $this->xml->addItem(compact('loc', 'lastmod', 'changefreq', 'priority'));
    }
}