<?php

namespace Helldar\Sitemap\Abstracts;

use Helldar\Sitemap\Contracts\ShowableContract;
use Helldar\Sitemap\Support\Xml;

abstract class SitemapAbstract implements ShowableContract
{
    /** @var Xml */
    protected $xml;

    public function __construct()
    {
        $this->xml = Xml::init();
    }

    public function show(): string
    {
        return $this->xml->get();
    }
}
