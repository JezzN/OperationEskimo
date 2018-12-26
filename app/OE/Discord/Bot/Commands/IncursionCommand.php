<?php

namespace App\OE\Discord\Bot\Commands;


use App\OE\Discord\Bot\Command;
use App\WoW\Incursion;
use Carbon\Carbon;
use Discord\Parts\Channel\Message;

class IncursionCommand extends Command
{
    private $incursion;

    const DATE_FORMAT = 'l jS \a\t ga';

    public function __construct(Incursion $incursion)
    {
        $this->incursion = $incursion;
    }

    public function execute(Message $message)
    {
        $reply = "";

        if ( $activeIncursion = $this->incursion->getActiveIncursion() ) {
            $activeIncursion['end_time'] = $activeIncursion['end_time']->setTimezone('Europe/Paris');
            $reply .= "!\n\nThere is an incursion active in " . $activeIncursion['zone'] . " for another " . Carbon::now()->diffForHumans($activeIncursion['end_time'], true) . " (finishing on " . $activeIncursion['end_time']->format(self::DATE_FORMAT) . ")\n\n";
        }

        $nextIncursion = $this->incursion->getNextIncursion();
        $nextIncursion['start_time'] = $nextIncursion['start_time']->setTimezone('Europe/Paris');
        $nextIncursion['end_time'] = $nextIncursion['end_time']->setTimezone('Europe/Paris');

        $reply .= "The next incursion starts on **" . $nextIncursion['start_time']->format(self::DATE_FORMAT) . "** (in " . Carbon::now()->diffForHumans($nextIncursion['start_time'], true, false, 3) . ") in " . $nextIncursion['zone'] . ' and ends on **' . $nextIncursion['end_time']->format(self::DATE_FORMAT) . '**.';

        $message->channel->sendMessage($reply);
    }
}