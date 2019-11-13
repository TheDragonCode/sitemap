<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use DateTimeInterface;
use Helldar\Sitemap\Contracts\DateContract;

class Date implements DateContract
{
    public static function parse($date = null): DateTimeInterface
    {
        return !empty($date)
            ? Carbon::parse($date)
            : Carbon::now();
    }
}
