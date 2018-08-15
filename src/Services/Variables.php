<?php

namespace Helldar\Sitemap\Services;

class Variables
{
    const FREQUENCY_ALWAYS  = 'always';

    const FREQUENCY_DAILY   = 'daily';

    const FREQUENCY_HOURLY  = 'hourly';

    const FREQUENCY_MONTHLY = 'monthly';

    const FREQUENCY_NEVER   = 'never';

    const FREQUENCY_WEEKLY  = 'weekly';

    const FREQUENCY_YEARLY  = 'yearly';

    /**
     * @return array
     */
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
}
