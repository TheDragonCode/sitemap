<?php

namespace Helldar\Sitemap\Traits;

trait Helpers
{
    /**
     * Escape HTML special characters in a string.
     *
     * @param $value
     *
     * @return string
     */
    private function e($value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * @param \Illuminate\Support\Collection $item
     * @param array $fields
     *
     * @return array
     */
    private function routeParameters($item, $fields = []): array
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
}
