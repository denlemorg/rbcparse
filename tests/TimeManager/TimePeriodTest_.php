<?php


namespace App\Tests\Service\TimeManager;


use App\Service\TimeManager\Time;
use PHPUnit\Framework\TestCase;

class TimePeriodTest_ extends TestCase
{
    public function testMinutesTo(): void
    {
        $this->assertEquals(60, 60);
    }
}