<?php

namespace Helldar\Sitemap\Contracts;

use DateTimeInterface;

interface DateContract
{
    public static function parse($value = null): DateTimeInterface;
}
