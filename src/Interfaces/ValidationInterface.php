<?php

namespace Helldar\Sitemap\Interfaces;

interface ValidationInterface
{
    public function __construct($items);

    public function get(): array;
}
