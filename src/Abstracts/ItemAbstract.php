<?php

namespace Helldar\Sitemap\Abstracts;

use DateTimeInterface;
use Helldar\Contracts\Sitemap\ItemContract;
use Helldar\Sitemap\Services\Date;
use Helldar\Sitemap\Services\Frequency;
use Helldar\Sitemap\Services\Priority;
use Helldar\Sitemap\Traits\Element;

abstract class ItemAbstract implements ItemContract
{
    use Element;

    public function changefreq(string $frequency): ItemContract
    {
        $value = Frequency::get($frequency);

        $this->setElement(__FUNCTION__, $value);

        return $this;
    }

    public function lastmod(DateTimeInterface $date = null): ItemContract
    {
        $value = Date::parse($date);

        $this->setElement(__FUNCTION__, $value->toAtomString());

        return $this;
    }

    public function loc(string $url): ItemContract
    {
        $this->setElement(__FUNCTION__, $url);

        return $this;
    }

    public function priority(float $value = 0.5): ItemContract
    {
        $value = Priority::get($value);

        $this->setElement(__FUNCTION__, $value);

        return $this;
    }
}
