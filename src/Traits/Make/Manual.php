<?php

namespace Helldar\Sitemap\Traits\Make;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Make\Sitemap;
use Helldar\Sitemap\Support\Xml;

trait Manual
{
    protected $manual = [];

    /** @var Xml */
    protected $xml;

    public function manual(array ...$items): Sitemap
    {
        $this->manual = $items;

        return $this;
    }

    protected function runManualProcess(): void
    {
        array_map(function ($items) {
            array_map(function (ItemContract $item) {
                $this->processManual($item);
            }, $items);
        }, $this->manual);
    }

    private function processManual(ItemContract $item): void
    {
        $this->xml->instance()
            ->appendToRoot(
                $item->toXml()
            );
    }
}
