<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Traits\Helpers;

class MakeItem
{
    use Helpers;

    /**
     * @var array
     */
    private $item = [];

    /**
     * Set the content update rate for the item.
     *
     * @param string $value
     *
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function changefreq(string $value = 'daily'): MakeItem
    {
        $frequencies = Variables::getFrequencies();

        $value = $this->lower(trim($value));
        $value = in_array($value, $frequencies) ? $value : Variables::FREQUENCY_DAILY;

        $this->setElement('changefreq', $value);

        return $this;
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param null|string|int $value
     *
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function lastmod($value = null): MakeItem
    {
        if (is_numeric($value)) {
            $value = Carbon::createFromTimestamp($value);
        } else {
            $value = $value ? Carbon::parse($value) : Carbon::now();
        }

        $this->setElement('lastmod', $value->toAtomString());

        return $this;
    }

    /**
     * Set the URL for the item.
     *
     * @param string $value
     *
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function loc(string $value): MakeItem
    {
        $this->setElement('loc', trim($value));

        return $this;
    }

    /**
     * Set the priority for the item.
     *
     * @param float $value
     *
     * @return \Helldar\Sitemap\Services\MakeItem
     */
    public function priority(float $value = 0.5): MakeItem
    {
        $value = ((float) $value < 0.1) ? 0.5 : $value;

        $this->setElement('priority', (float) $value);

        return $this;
    }

    /**
     * Get the item to save to the site map.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->item;
    }

    /**
     * Add an item parameter to the resulting array.
     *
     * @param string $key
     * @param string $value
     */
    private function setElement(string $key, string $value)
    {
        $this->item[$key] = $value;
    }
}
