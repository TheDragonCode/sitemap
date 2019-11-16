<?php

namespace Helldar\Sitemap\Traits\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait GetAttributes
{
    protected function getAttributes(): array
    {
        $items = get_object_vars($this);

        array_walk($items, function (&$item, $key, $properties) {
            $value = Arr::get($properties, $key);

            $item = $this->getAttribute($key, $value);
        }, $items);

        return $items;
    }

    /**
     * @param string $key
     * @param $value
     *
     * @return mixed
     */
    private function getAttribute(string $key, $value)
    {
        $method = $this->methodName($key);

        return method_exists($this, $method)
            ? call_user_func([$this, $method], $value)
            : $value;
    }

    private function methodName(string $key): string
    {
        $name = "get_{$key}_attribute";

        return Str::studly($name);
    }
}
