<?php

namespace Helldar\Sitemap\Traits;

use Helldar\Contracts\Sitemap\SitemapContract;
use Helldar\Sitemap\Services\Config;

trait Domain
{
    protected $url;

    public function url(string $domain): SitemapContract
    {
        $this->url = Config::get("domains.{$domain}", '/');

        return $this;
    }
}
