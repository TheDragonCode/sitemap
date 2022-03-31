<?php

namespace Helldar\Sitemap\Helpers;

use Carbon\Carbon;
use DragonCode\Core\Xml\Helpers\Str;
use function in_array;
use function is_numeric;
use function trim;

class Variables
{
    public const FREQUENCY_ALWAYS  = 'always';

    public const FREQUENCY_DAILY   = 'daily';

    public const FREQUENCY_HOURLY  = 'hourly';

    public const FREQUENCY_MONTHLY = 'monthly';

    public const FREQUENCY_NEVER   = 'never';

    public const FREQUENCY_WEEKLY  = 'weekly';

    public const FREQUENCY_YEARLY  = 'yearly';

    public const PRIORITY_DEFAULT  = 0.5;

    public static function getFrequencies(): array
    {
        return [
            self::FREQUENCY_ALWAYS,
            self::FREQUENCY_DAILY,
            self::FREQUENCY_HOURLY,
            self::FREQUENCY_MONTHLY,
            self::FREQUENCY_NEVER,
            self::FREQUENCY_WEEKLY,
            self::FREQUENCY_YEARLY,
        ];
    }

    public static function correctFrequency(string $frequency = 'daily'): string
    {
        $frequency = Str::lower(trim($frequency));

        return in_array($frequency, self::getFrequencies()) ? $frequency : self::FREQUENCY_DAILY;
    }

    public static function correctPriority(float $priority = 0.5): float
    {
        if ($priority >= 0.1 && $priority <= 1) {
            return $priority;
        }

        return self::PRIORITY_DEFAULT;
    }

    public static function getDate($value = null): Carbon
    {
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        return $value ? Carbon::parse($value) : Carbon::now();
    }
}
