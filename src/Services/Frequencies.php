<?php

namespace Helldar\Sitemap\Services;

use Helldar\Sitemap\Constants\Frequency;
use Helldar\Sitemap\Contracts\FrequencyContract;

class Frequencies implements FrequencyContract
{
    static public function all(): array
    {
        return [
            Frequency::ALWAYS,
            Frequency::DAILY,
            Frequency::HOURLY,
            Frequency::MONTHLY,
            Frequency::WEEKLY,
            Frequency::YEARLY,
            Frequency::NEVER,
        ];
    }

    static public function get(string $frequency = Frequency::DAILY): string
    {
        $frequency = Str::lower($frequency);

        return self::exists($frequency)
            ? $frequency
            : Frequency::DAILY;
    }

    static public function exists(string $frequency = Frequency::DAILY): bool
    {
        return in_array($frequency, self::all());
    }
}
