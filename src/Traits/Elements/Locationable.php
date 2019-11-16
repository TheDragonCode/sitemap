<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Support\Exceptions\NotValidUrlException;
use Helldar\Support\Facades\Http;

trait Locationable
{
    protected $loc;

    public function loc(string $url): ItemContract
    {
        $this->loc = $url;

        return $this;
    }

    /**
     * @throws NotValidUrlException
     *
     * @return string
     */
    protected function getLocAttribute(): string
    {
        if (Http::isUrl($this->loc)) {
            return $this->loc;
        }

        throw new NotValidUrlException($this->loc);
    }
}
