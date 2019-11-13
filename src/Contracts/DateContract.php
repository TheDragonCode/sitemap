<?php

namespace Helldar\Sitemap\Contracts;

use DateTimeInterface;

interface DateContract
{
    static public function parse($value = null): DateTimeInterface;
}
