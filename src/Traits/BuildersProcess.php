<?php

namespace Helldar\Sitemap\Traits;

use Helldar\Contracts\Sitemap\SitemapContract;
use Illuminate\Database\Eloquent\Builder;

trait BuildersProcess
{
    protected $builders = [];

    public function builders(Builder ...$builders): SitemapContract
    {
        $this->builders = (array) $builders;

        return $this;
    }

    protected function processBuilder(Builder $builder): void
    {
    }
}
