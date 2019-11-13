<?php

namespace Helldar\Sitemap\Contracts;

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
