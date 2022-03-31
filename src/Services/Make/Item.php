<?php

namespace Helldar\Sitemap\Services\Make;

use DragonCode\Core\Xml\Abstracts\Item as ItemAbstract;
use DragonCode\Core\Xml\Interfaces\ItemInterface;
use Helldar\Sitemap\Helpers\Variables;
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
     * @return \Helldar\Sitemap\Services\Make\Item
     */
    public function changefreq(string $value = 'daily'): self
    {
        $value = Variables::correctFrequency($value);

        $this->setElement('changefreq', $value);

        return $this;
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param int|string|null $value
     *
     * @return \Helldar\Sitemap\Services\Make\Item
     */
    public function lastmod($value = null): self
    {
        $date = Variables::getDate($value);

        $this->setElement('lastmod', $date->toAtomString());

        return $this;
    }

    /**
     * Set the URL for the item.
     *
     * @param string $value
     *
     * @return \Helldar\Sitemap\Services\Make\Item
     */
    public function loc(string $value): self
    {
        $this->setElement('loc', trim($value));

        return $this;
    }

    /**
     * Set the priority for the item.
     *
     * @param float $value
     *
     * @return \Helldar\Sitemap\Services\Make\Item
     */
    public function priority(float $value = 0.5): self
    {
        $value = Variables::correctPriority($value);

        $this->setElement('priority', $value);

        return $this;
    }
}
