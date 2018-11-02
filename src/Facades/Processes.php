<?php

namespace Helldar\Sitemap\Facades;

abstract class Processes
{
    /** @var \Helldar\Sitemap\Services\Xml */
    protected $xml;

    protected $item = [];

    /**
     * Escape HTML special characters in a string.
     *
     * @param $value
     *
     * @return string
     */
    protected function e($value): string
    {
        if (empty($value)) {
            return null;
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * @param \Illuminate\Support\Collection $item
     * @param array $fields
     *
     * @return array
     */
    protected function routeParameters($item, $fields = []): array
    {
        $result = [];

        foreach ($fields as $key => $value) {
            $key   = is_numeric($key) ? $value : $key;
            $value = $item->{$value} ?? false;

            if ($value) {
                $result[$key] = $value;
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
        if (!empty($value)) {
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

        if (!array_key_exists($key, $this->item)) {
            $this->item[$key] = [];
        }

        array_push($this->item[$key], $value);
    }
}
