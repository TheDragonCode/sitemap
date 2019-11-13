<?php

namespace Helldar\Sitemap\Services;

use Carbon\Carbon;
use DateTimeInterface;
use Helldar\Sitemap\Contracts\DateContract;

use function is_numeric;

class Dates implements DateContract
{
    static public function parse($value = null): DateTimeInterface
    {
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        return $value ? Carbon::parse($value) : Carbon::now();
    }
}
