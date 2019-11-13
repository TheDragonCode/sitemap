<?php

namespace Helldar\Sitemap\Contracts;

interface SitemapContract
{
    public function url(string $domain): self;
}
