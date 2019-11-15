<?php

namespace Helldar\Sitemap\Contracts\Sitemap;

interface MakeItemContract
{
    public function item(): ItemContract;
}
