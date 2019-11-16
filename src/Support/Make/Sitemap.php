<?php

namespace Helldar\Sitemap\Support\Make;

use Helldar\Sitemap\Abstracts\SitemapAbstract;
use Helldar\Sitemap\Contracts\Sitemap\MakeItemContract;
use Helldar\Sitemap\Contracts\Sitemap\ManualContract;
use Helldar\Sitemap\Contracts\StorableContract;
use Helldar\Sitemap\Traits\Make\MakeItem;
use Helldar\Sitemap\Traits\Make\Manual;
use Helldar\Sitemap\Traits\Support\Storable;

class Sitemap extends SitemapAbstract implements MakeItemContract, ManualContract, StorableContract
{
    use MakeItem;
    use Manual;
    use Storable;
}
