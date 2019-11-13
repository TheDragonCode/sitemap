<?php

namespace Helldar\Contracts\Sitemap;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

interface SitemapContract
{
    public function item(): ItemContract;

    public function builders(Builder ...$builders): SitemapContract;

    public function manual(ItemContract ...$items): SitemapContract;

    public function show(): Response;

    public function save(): bool;
}
