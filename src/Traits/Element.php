<?php

namespace Helldar\Sitemap\Traits;

trait Element
{
    protected $element = [];

    public function get(): array
    {
        return $this->element;
    }

    protected function setElement(string $key, $value): void
    {
        if (!empty($value)) {
            $this->element[$key] = $value;
        }
    }

    protected function addElement(string $key, $value): void
    {
        if (empty($value)) {
            return;
        }

        if (!array_key_exists($key, $this->element)) {
            $this->element[$key] = [];
        }

        array_push($this->element[$key], $value);
    }
}
