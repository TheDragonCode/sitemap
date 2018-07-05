<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use Helldar\Sitemap\Traits\Helpers;

class MakeItem
{
    use Helpers;

    const FREQ = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];

    /**
     * @var array
     */
    private $item = [];

    /**
     * @param string $value
     *
     * @return $this
     */
    public function changefreq($value = 'daily')
    {
        $value = $this->lower(trim($value));
        $value = !in_array($value, self::FREQ) ? 'daily' : $value;

        $this->setElement('changefreq', $value);

        return $this;
    }

    /**
     * @param null $value
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
     * @param float $value
     *
     * @return $this
     */
    public function priority($value = 0.5)
    {
        $this->setElement('priority', (float) $value);

        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->item;
    }

    /**
     * @param $key
     * @param $value
     */
    private function setElement($key, $value)
    {
        $this->item[$key] = $value;
    }
}
