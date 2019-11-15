<?php

namespace Helldar\Sitemap\Contracts\Support;

use DateTimeInterface;

interface DateContract
{
    /**
     * @param null|string|DateTimeInterface $date
     *
     * @return DateTimeInterface
     */
    public static function parse($date = null): DateTimeInterface;
}
