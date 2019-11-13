<?php

namespace Helldar\Sitemap\Services\Make;

use Helldar\Core\Xml\Abstracts\Item as ItemAbstract;
use Helldar\Core\Xml\Interfaces\ItemInterface;
use Helldar\Sitemap\Services\Dates;
use Helldar\Sitemap\Services\Frequencies;
use Helldar\Sitemap\Services\Priorities;
use Helldar\Sitemap\Traits\Helpers;

use function trim;

class Item extends ItemAbstract implements ItemInterface
{
    use Helpers;

    /**
     * Set the content update rate for the item.
     *
     * @param string $value
     *
     * @return Item
     */
    public function changefreq(string $value = 'daily'): self
    {
        $value = Frequencies::get($value);

        $this->setElement(__FUNCTION__, $value);

        return $this;
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param null|string|int $value
     *
     * @return Item
     */
    public function lastmod($value = null): self
    {
        $date = Dates::parse($value);

        $this->setElement(__FUNCTION__, $date->toAtomString());

        return $this;
    }

    /**
     * Set the URL for the item.
     *
     * @param string $value
     *
     * @return Item
     */
    public function loc(string $value): self
    {
        $this->setElement(__FUNCTION__, trim($value));

        return $this;
    }

    /**
     * Set the priority for the item.
     *
     * @param float $value
     *
     * @return Item
     */
    public function priority(float $value = 0.5): self
    {
        $value = Priorities::get($value);

        $this->setElement(__FUNCTION__, $value);

        return $this;
    }
}
