<?php

namespace App\WoW;


use Carbon\Carbon;

class Incursion
{
    const INCURSION_RATE = 60 * 60 * 12;

    const INCURSION_DURATION = 60 * 60 * 7;

    private $incurstions;

    private $zones = ['Vol\'dun', 'Drustvar', 'Zuldazar', 'Tirigarde Sound', 'Nazmir', 'Stormsong Valley'];

    public function __construct()
    {
        $this->addIncursion(new Carbon('2018-12-25 13:00:00'), 'Nazmir');
        $this->buildIncursionList();
    }

    public function getActiveIncursion() {
       $time = Carbon::now();

       foreach($this->incurstions as $incurstion) {

           if ($time->between($incurstion['start_time'], $incurstion['end_time'])) {
               return $incurstion;
           }

           if ($time->lt($incurstion['start_time']) ){
               return null;
           }
       }

       return null;
    }

    public function getNextIncursion($time = null) {
        if (!$time) {
            $time = Carbon::now();
        }

        foreach ($this->incurstions as $incurstion) {
            if($incurstion['start_time']->gt($time)) {
                return $incurstion;
            }
        }
    }

    private function buildIncursionList()
    {
        for($i = 1; $i < 1000; $i++) {
            $previousIncurstion = end($this->incurstions);

            $this->addIncursion(
                $previousIncurstion['end_time']->copy()->addSecond(self::INCURSION_RATE),
                $this->getNextZone($previousIncurstion['zone'])
            );
        }
    }

    private function getNextZone($previousZone) {
        $index = array_search($previousZone, $this->zones);

        if (count($this->zones) > ($index + 1)) {
            return $this->zones[$index + 1];
        }

        return $this->zones[0];
    }

    private function addIncursion(Carbon $start, $zone) {
        $this->incurstions[] = [
            'start_time' => $start,
            'end_time' => $start->copy()->addSecond(self::INCURSION_DURATION),
            'zone' => $zone
        ];
    }


}