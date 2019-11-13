<?php

namespace Helldar\Sitemap\Traits;

trait Process
{
    /** @var \Helldar\Core\Xml\Facades\Xml */
    protected $xml;

    protected function get(array $except = []): string
    {
        array_map(function ($builder) {
            $this->processBuilder($builder);
        }, $this->builders);

        array_map(function ($items) {
            array_map(function ($item) {
                $this->processManuals($item);
            }, $items);
        }, $this->manuals);

        return $this->xml->get();
    }
}
