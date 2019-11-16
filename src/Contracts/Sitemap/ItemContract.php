<?php

namespace Helldar\Sitemap\Contracts\Sitemap;

use Helldar\Sitemap\Contracts\Elements\FrequencyContract;
use Helldar\Sitemap\Contracts\Elements\LastModificationContract;
use Helldar\Sitemap\Contracts\Elements\LocationContract;
use Helldar\Sitemap\Contracts\Elements\PriorityContract;
use Helldar\Sitemap\Contracts\Support\ArrayableContract;
use Helldar\Sitemap\Contracts\XmlableContract;

interface ItemContract extends FrequencyContract, LastModificationContract, LocationContract, PriorityContract, XmlableContract, ArrayableContract
{
    //
}
