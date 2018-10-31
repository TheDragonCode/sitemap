<?php

namespace Helldar\Sitemap\Interfaces;

interface ValidationInterface
{
    public function __construct(array $items = []);

    public function get(): array;
}
