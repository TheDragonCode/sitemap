<?php

namespace Helldar\Sitemap\Contracts;

interface ImagesItemContract
{
    public function image(string $loc, string $title = null, string $caption = null, string $geo_location = null, string $license = null);
}
