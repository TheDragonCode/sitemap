<?php

namespace Helldar\Sitemap\Services\Items;

use Helldar\Sitemap\Interfaces\MakeElementsInterface;
use Helldar\Sitemap\Traits\Helpers;

class MakeImages implements MakeElementsInterface
{
    use Helpers;

    private $item = [];

    public function loc(string $value): self
    {
        $this->setElement('loc', trim($value));

        return $this;
    }

    public function image(string $loc, string $title = null, string $caption = null, string $geo_location = null, string $license = null): self
    {
        $image = array_filter(compact('loc', 'title', 'caption', 'geo_location', 'license'));

        $this->pushElement('images', $image);

        return $this;
    }

    public function get(): array
    {
        return $this->item;
    }
}
