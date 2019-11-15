<?php

namespace Tests\Support;

use Helldar\Sitemap\Support\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testFormatOutput()
    {
        $actual = Config::get('format_output');

        $this->assertIsBool($actual);
        $this->assertFalse($actual);
    }
}
