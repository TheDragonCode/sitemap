<?php

namespace Helldar\Sitemap\Services\Make;

use function array_filter;
use function compact;
use Helldar\Core\Xml\Abstracts\Item as ItemAbstract;
use Helldar\Core\Xml\Interfaces\ItemInterface;

use Helldar\Sitemap\Contracts\ImagesItemContract;
use Helldar\Sitemap\Traits\Helpers;
use function trim;

class Images extends ItemAbstract implements ItemInterface, ImagesItemContract
{
    use Helpers;

    public function loc(string $value): self
    {
        $this->setElement(__FUNCTION__, trim($value));

        return $this;
    }

    public function image(string $loc, string $title = null, string $caption = null, string $geo_location = null, string $license = null): self
    {
        $image = array_filter(compact('loc', 'title', 'caption', 'geo_location', 'license'));

        $this->pushElement('images', $image);

        return $this;
    }
}
