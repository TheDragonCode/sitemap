<?php

namespace Helldar\Sitemap\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Variables
{
    const FREQUENCY_ALWAYS  = 'always';

    const FREQUENCY_DAILY   = 'daily';

    const FREQUENCY_HOURLY  = 'hourly';

    const FREQUENCY_MONTHLY = 'monthly';

    const FREQUENCY_NEVER   = 'never';

    const FREQUENCY_WEEKLY  = 'weekly';

    const FREQUENCY_YEARLY  = 'yearly';

    const PRIORITY_DEFAULT  = 0.5;

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
        $frequency = Str::lower(\trim($frequency));

        return \in_array($frequency, self::getFrequencies()) ? $frequency : self::FREQUENCY_DAILY;
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
        if (\is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        return $value ? Carbon::parse($value) : Carbon::now();
    }

    public static function toArray($object): array
    {
        foreach ($object as &$item) {
            if (\is_object($item)) {
                if (\method_exists($item, 'get')) {
                    $item = $item->get();
                } else {
                    $item = (array) $item;
                }
            }

            if (\is_array($item) || \is_object($item)) {
                self::toArray($item);
            }
        }

        return $object;
    }
}
