<?php

namespace Helldar\Sitemap\Traits\Elements;

use Helldar\Sitemap\Constants\Frequency as Freq;
use Helldar\Sitemap\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Support\Frequency;

trait Frequenciable
{
    protected $changefreq = Freq::DAILY;

    public function changefreq(string $frequency): ItemContract
    {
        $this->changefreq = $frequency;

        return $this;
    }

    protected function getChangefreqAttribute(): string
    {
        return Frequency::get($this->changefreq);
    }
}
