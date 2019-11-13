<?php

namespace Helldar\Sitemap\Contracts;

use Helldar\Sitemap\Constants\Frequency;

interface FrequencyContract
{
    public static function all(): array;

    public static function get(string $frequency = Frequency::DAILY): string;

    public static function exists(string $frequency = Frequency::DAILY): bool;
}
