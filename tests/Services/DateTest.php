<?php

namespace Tests\Services;

use Carbon\Carbon;
use DateTimeInterface;
use Helldar\Sitemap\Services\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function testParseValue()
    {
        $expected = '2019-11-13 23:43';
        $parsed   = Date::parse($expected);

        $this->assertEquals($expected, $parsed->format('Y-m-d H:i'));
    }

    public function testParseNull()
    {
        $parsed = Date::parse();

        $this->assertTrue($parsed instanceof DateTimeInterface);
    }

    public function testParseDate()
    {
        $date   = Carbon::now();
        $parsed = Date::parse($date);

        $this->assertEquals($date, $parsed);
    }
}
