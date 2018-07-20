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
     * @return $this
     */
    public function changefreq($value = 'daily')
    {
        $value = $this->lower(trim($value));
        $value = in_array($value, $this->getFrequencies()) ? $value : Sitemap::FREQUENCY_DAILY;

        $this->setElement('changefreq', $value);

        return $this;
    }

    /**
     * Set the date when the content was last updated.
     *
     * @param null|string|int $value
     *
     * @return $this
     */
    public function lastmod($value = null)
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
     * @param $value
     *
     * @return $this
     */
    public function loc($value)
    {
        $this->setElement('loc', trim($value));

        return $this;
    }

    /**
     * Set the priority for the item.
     *
     * @param float $value
     *
     * @return $this
     */
    public function priority($value = 0.5)
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
    public function get()
    {
        return $this->item;
    }

    /**
     * Add an item parameter to the resulting array.
     *
     * @param string $key
     * @param string $value
     */
    private function setElement($key, $value)
    {
        $this->item[$key] = $value;
    }

    /**
     * @return array
     */
    private function getFrequencies()
    {
        return [
            Sitemap::FREQUENCY_ALWAYS,
            Sitemap::FREQUENCY_DAILY,
            Sitemap::FREQUENCY_HOURLY,
            Sitemap::FREQUENCY_MONTHLY,
            Sitemap::FREQUENCY_NEVER,
            Sitemap::FREQUENCY_WEEKLY,
            Sitemap::FREQUENCY_YEARLY,
        ];
    }
}
