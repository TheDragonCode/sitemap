<?php

namespace Helldar\Sitemap\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use function array_key_exists;
use function array_push;
use function is_numeric;
use function storage_path;

trait Helpers
{
    /**
     * @param Collection $item
     * @param array $fields
     *
     * @return array
     */
    protected function routeParameters($item, $fields = []): array
    {
        $result = [];

        foreach ($fields as $key => $value) {
            $key   = is_numeric($key) ? $value : $key;
            $value = Arr::get($item, $value, false);

            if ($value) {
                Arr::set($result, $key, $value);
            }
        }

        return $result;
    }

    /**
     * Add an item parameter to the resulting array.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setElement(string $key, $value)
    {
        if (! empty($value)) {
            $this->item[$key] = $value;
        }
    }

    /**
     * Push an item parameter or array of parameters to the resulting array.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function pushElement(string $key, $value)
    {
        if (empty($value)) {
            return;
        }

        if (! array_key_exists($key, $this->item)) {
            $this->item[$key] = [];
        }

        array_push($this->item[$key], $value);
    }
}
