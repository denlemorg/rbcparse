<?php

namespace App\Service\TimeManager;


use stringEncode\Exception;

class Time
{
    /**
     * @var int
     */
    private $hours;
    /**
     * @var int
     */
    private $minutes;

    /**
     * Time constructor.
     * @param $hours
     * @param $minutes
     */
    private function __construct(int $hours, int $minutes)
    {
        $this->hours = $hours;
        $this->minutes = $minutes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $minuteToString = ($this->minutes < 10) ? "0" . $this->minutes : $this->minutes;
        $hoursToString = ($this->hours < 10) ? "0" . $this->hours : $this->hours;
        return $hoursToString . ":" . $minuteToString;
    }

    /**
     * @param string $str
     * @return static
     * @throws Exception
     */
    public static function createFromString(string $str): self
    {
        if (self::isValid($str)) {
            $dataValues = self::setFormatData($str);
            if (self::postValid($dataValues)) {
                $time = new Time($dataValues['hours'], $dataValues['minutes']);
                return $time;
            }
        }
        throw new Exception('Неверные исходные данные');
    }

    /**
     * @param string $str
     * @return bool
     */
    private static function isValid(string $str): bool
    {
        if (preg_match('/^\d{1,2}:\d{1,2}$/', $str)) {
            return true;
        }
        return false;
    }

    /**
     * @param array $dataValues
     * @return bool
     */
    private static function postValid(array $dataValues): bool
    {
        if ($dataValues['hours']>=0 && $dataValues['hours']<=23
            && $dataValues['minutes']>=0 && $dataValues['minutes']<=59 ) {
            return true;
        }
        return false;
    }
    /**
     * @param string $str
     * @return array|int[]
     */
    private static function setFormatData(string $str): array
    {
        $t = explode(':', $str);
        return ["hours"=>(int) $t[0], "minutes"=> (int) $t[1]];
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }

    /**
     * @param Time $time
     * @return int
     */
    public function compareTo(Time $t): int
    {
        if ($t->hours > $this->hours || ($t->hours == $this->hours && $t->minutes > $this->minutes)) {
            return -1;
        } elseif ($t->hours < $this->hours || ( $t->hours == $this->hours && $t->minutes < $this->minutes)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function minutesTo(Time $t): int
    {
        return $this->hours*60 + $this->minutes - $t->hours*60 - $t->minutes;
    }

    public function secondsTo(Time $t): int
    {
        return $this->minutesTo($t) * 60;
    }


}
