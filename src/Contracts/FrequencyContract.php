<?php

namespace Helldar\Sitemap\Contracts;

use Helldar\Sitemap\Constants\Frequency;

interface FrequencyContract
{
    static public function all(): array;

    static public function get(string $frequency = Frequency::DAILY): string;

    static public function exists(string $frequency = Frequency::DAILY): bool;
}
