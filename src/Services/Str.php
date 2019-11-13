<?php

namespace Helldar\Sitemap\Services;

use Helldar\Core\Xml\Helpers\Str as StrHelper;

use function trim;

class Str
{
    public static function lower(string $value): string
    {
        return StrHelper::lower(trim($value));
    }
}