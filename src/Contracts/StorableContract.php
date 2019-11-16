<?php

namespace Helldar\Sitemap\Contracts;

interface StorableContract
{
    public function store(string $filename = null): bool;
}
