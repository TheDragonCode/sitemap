<?php

namespace Helldar\Sitemap\Support\Make;

use Helldar\Sitemap\Abstracts\SitemapAbstract;
use Helldar\Sitemap\Contracts\Sitemap\MakeItemContract;
use Helldar\Sitemap\Traits\Make\MakeItem;

class Sitemap extends SitemapAbstract implements MakeItemContract
{
    use MakeItem;
}
