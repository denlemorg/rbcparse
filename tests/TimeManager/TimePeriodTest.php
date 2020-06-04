<?php


namespace App\Tests\Service\TimeManager;


use App\Service\TimeManager\Time;
use App\Service\TimeManager\TimePeriod;
use PHPUnit\Framework\TestCase;

class TimePeriodTest extends TestCase
{
    public function test__construct(): void
    {
        $time1 = Time::createFromString('20:14');
        $time2 = Time::createFromString('19:14');
        $timePeriod = new TimePeriod($time1, $time2);
        $timeStart = $timePeriod->getTimeStart();
        $timeFinish = $timePeriod->getTimeFinish();
        $this->assertInstanceOf(Time::class, $timeStart);
        $this->assertInstanceOf(Time::class, $timeFinish);
    }

    public function testIsValidProperties(): void
    {
        $time1 = Time::createFromString('20:14');
        $time2 = Time::createFromString('19:14');
        $timePeriod = new TimePeriod($time1, $time2);

        $timeStart = $timePeriod->getTimeStart();
        $timeFinish = $timePeriod->getTimeFinish();

        $this->assertIsInt($timeStart->getHours());
        $this->assertIsInt($timeStart->getMinutes());
        $this->assertIsInt($timeFinish->getHours());
        $this->assertIsInt($timeFinish->getMinutes());
    }

    public function testRevert(): void
    {
        $time1 = Time::createFromString('20:14');
        $time2 = Time::createFromString('19:14');
        $timePeriod = new TimePeriod($time1, $time2);

        $timeStart = $timePeriod->getTimeStart();
        $timeFinish = $timePeriod->getTimeFinish();

        $this->assertEquals(1, $timeFinish->compareTo($timeStart));

    }
    public function testHas(): void
    {
        $time1 = Time::createFromString('19:14');
        $time2 = Time::createFromString('20:14');
        $timePeriod = new TimePeriod($time1, $time2);

        $time3 = Time::createFromString('19:37');

        $this->assertTrue($timePeriod->has($time3));
    }

}