<?php

namespace Tests\Constants;

use Helldar\Sitemap\Constants\Frequency;
use PHPUnit\Framework\TestCase;

class FrequencyTest extends TestCase
{
    public function testAlways()
    {
        $this->assertEquals('always', Frequency::ALWAYS);
    }

    public function testDaily()
    {
        $this->assertEquals('daily', Frequency::DAILY);
    }

    public function testHourly()
    {
        $this->assertEquals('hourly', Frequency::HOURLY);
    }

    public function testMontly()
    {
        $this->assertEquals('monthly', Frequency::MONTHLY);
    }

    public function testNever()
    {
        $this->assertEquals('never', Frequency::NEVER);
    }

    public function testWeekly()
    {
        $this->assertEquals('weekly', Frequency::WEEKLY);
    }

    public function testYearly()
    {
        $this->assertEquals('yearly', Frequency::YEARLY);
    }
}
