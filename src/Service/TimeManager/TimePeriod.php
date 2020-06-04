<?php


namespace App\Service\TimeManager;

use App\Service\TimeManager\Time;

class TimePeriod
{
    /**
     * @var Time;
     */
    private $timeStart;
    /**
     * @var Time;
     */
    private $timeFinish;

    /**
     * TimePeriod constructor.
     * @param Time $timeStart
     * @param Time $timeFinish
     */
    public function __construct(Time $timeStart, Time $timeFinish)
    {
        if ( $timeFinish->compareTo($timeStart) == 1 ) {
            $this->timeStart = $timeStart;
            $this->timeFinish = $timeFinish;
        }else{
            $this->timeStart = $timeFinish;
            $this->timeFinish = $timeStart;
        }
    }

    /**
     * @param Time $t
     * @return bool
     */
    public function has(Time $t): bool
    {
        if ($this->timeStart->compareTo($t) == -1 && $this->timeFinish->compareTo($t) == 1){
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function lengthInMinutes(): int
    {
        return $this->timeStart->minutesTo($this->timeFinish);
    }

    /**
     * @return int
     */
    public function lengthInSeconds(): int
    {
        return $this->timeStart->secondsTo($this->timeFinish);
    }

    /**
     * @return Time
     */
    public function getTimeFinish(): Time
    {
        return $this->timeFinish;
    }

    /**
     * @return Time
     */
    public function getTimeStart(): Time
    {
        return $this->timeStart;
    }
}