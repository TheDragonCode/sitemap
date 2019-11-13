<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Constants\Frequency as FrequencyConstant;
use Helldar\Sitemap\Contracts\FrequencyContract;

class Frequency implements FrequencyContract
{
    public static function all(): array
    {
        return [
            FrequencyConstant::DAILY,
            FrequencyConstant::HOURLY,
            FrequencyConstant::MONTHLY,
            FrequencyConstant::WEEKLY,
            FrequencyConstant::YEARLY,
            FrequencyConstant::ALWAYS,
            FrequencyConstant::NEVER,
        ];
    }

    public static function get(string $frequency = null): string
    {
        return self::exists($frequency)
            ? $frequency
            : FrequencyConstant::DAILY;
    }

    public static function exists(string $frequency = null): bool
    {
        return in_array($frequency, self::all(), true);
    }
}
