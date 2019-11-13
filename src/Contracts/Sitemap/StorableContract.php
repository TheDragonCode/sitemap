<?php

namespace Helldar\Sitemap\Contracts\Sitemap;

interface StorableContract
{
    public static function save(string $path = null): bool;
}
