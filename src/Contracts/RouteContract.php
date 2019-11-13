<?php

namespace Helldar\Sitemap\Contracts;

interface RouteContract
{
    public static function parameters($item, array $fields = []): array;
}
