<?php

namespace Helldar\Sitemap\Support;

use Carbon\Carbon;
use DateTimeInterface;
use DateTimeZone;
use Helldar\Sitemap\Contracts\Support\DateContract;

class Date implements DateContract
{
    /**
     * @param DateTimeInterface|string|null $date
     * @param DateTimeZone|string|null $tz
     *
     * @return DateTimeInterface
     */
    public static function parse($date = null, $tz = null): DateTimeInterface
    {
        return !empty($date)
            ? Carbon::parse($date, $tz)
            : Carbon::now($tz);
    }
}
