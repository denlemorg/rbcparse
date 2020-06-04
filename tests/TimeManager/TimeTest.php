<?php
declare(strict_types=1);

namespace App\Tests\Service\TimeManager;

use App\Service\TimeManager\Time;
use PHPUnit\Framework\TestCase;


class TimeTest extends TestCase
{
    public function testCreateFromString(): void
    {

        $time = Time::createFromString('20:14');
        $this->assertEquals($time->getHours(), 20);
        $this->assertEquals($time->getMinutes(), 14);

    }

    public function testNotCorrectData(): void
    {
        $this->expectException(\Exception::class);
        $time = Time::createFromString('fgkjdshfkfsjf');
    }

    public function testNotCorrectDataHours(): void
    {
        $this->expectException(\Exception::class);
        $time = Time::createFromString('25:14');
    }

    public function testNotCorrectDataMinutes(): void
    {
        $this->expectException(\Exception::class);
        $time = Time::createFromString('23:74');
    }

    public function testTypesProperties(): void
    {
        $time = Time::createFromString('20:14');
        $this->assertIsInt($time->getHours());
        $this->assertIsInt($time->getMinutes());
    }

    public function test__toString(): void
    {
        $time = Time::createFromString('20:14');
        $toString = $time->__toString();
        $this->assertEquals('20:14', $toString);
    }

    public function testCompareTo(): void
    {
        $time1 = Time::createFromString('20:14');
        $time2 = Time::createFromString('20:14');
        $this->assertEquals($time1, $time2);
    }

    public function testMinutesTo(): void
    {
        $time1 = Time::createFromString('20:14');

        $time2 = Time::createFromString('19:14');
        $minutesTo = $time1->minutesTo($time2);
        $this->assertEquals(60, $minutesTo);

        $time2 = Time::createFromString('20:14');
        $minutesTo = $time1->minutesTo($time2);
        $this->assertEquals(0, $minutesTo);

        $time2 = Time::createFromString('21:24');
        $minutesTo = $time1->minutesTo($time2);
        $this->assertEquals(-70, $minutesTo);
    }

    public function testSecondsTo(): void
    {
        $time1 = Time::createFromString('20:14');

        $time2 = Time::createFromString('19:14');
        $secondsTo = $time1->secondsTo($time2);
        $this->assertEquals(3600, $secondsTo);

        $time2 = Time::createFromString('20:14');
        $secondsTo = $time1->secondsTo($time2);
        $this->assertEquals(0, $secondsTo);

        $time2 = Time::createFromString('21:16');
        $secondsTo = $time1->secondsTo($time2);
        $this->assertEquals(-3720, $secondsTo);
    }


//    Как протестировать приватные методы isValid, setFormatData, postValid?
//    И нужно ли их вообще тестировать?
//    public function testIsValid(): void
//    {
//        $this->markTestIncomplete(
//            'Этот тест ещё не реализован.'
//        );

//    }


}