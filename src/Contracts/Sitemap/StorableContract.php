<?php

namespace Helldar\Sitemap\Contracts\Sitemap;

interface StorableContract
{
    static public function save(string $path = null): bool;
}
