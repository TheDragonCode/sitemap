<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Support\Exceptions\NotValidUrlException;
use Helldar\Support\Facades\Http;

trait Locationable
{
    protected $loc;

    /**
     * @param string $url
     *
     * @throws NotValidUrlException
     *
     * @return ItemContract
     */
    public function loc(string $url): ItemContract
    {
        if (!Http::isUrl($url)) {
            throw new NotValidUrlException($url);
        }

        $this->loc = $url;

        return $this;
    }
}
