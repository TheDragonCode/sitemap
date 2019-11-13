<?php

use Helldar\Contracts\Sitemap\SitemapContract;
use Helldar\Sitemap\Services\Make\Sitemap;
use Helldar\Sitemap\Services\Xml;

if (! function_exists('sitemap')) {
    function sitemap(): SitemapContract
    {
        return new Sitemap(new Xml());
    }
}
