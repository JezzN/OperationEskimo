<?php

namespace App\OE\Discord\Bot\Commands;


use App\OE\Discord\Bot\Command;
use App\WoW\Incursion;
use Carbon\Carbon;
use Discord\Parts\Channel\Message;

class IncursionCommand extends Command
{
    private $incursion;

    public function __construct(Incursion $incursion)
    {
        $this->incursion = $incursion;
    }

    public function execute(Message $message)
    {
        $reply = "";

        if ( $activeIncursion = $this->incursion->getActiveIncursion() ) {
            $reply .= "!\n\n**There is an incursion active in " . $activeIncursion['zone'] . " for another " . Carbon::now()->diffForHumans($activeIncursion['end_time'], true) . ".**\n\n";
        }

        $nextIncursion = $this->incursion->getNextIncursion();
        $nextIncursion['start_time'] = $nextIncursion['start_time']->setTimezone('Europe/Paris');
        $nextIncursion['end_time'] = $nextIncursion['end_time']->setTimezone('Europe/Paris');

        $reply .= "The next incursion starts on **" . $nextIncursion['start_time']->format('l jS \a\t ga') . "** (in " . Carbon::now()->diffForHumans($nextIncursion['start_time'], true, false, 3) . ") in " . $nextIncursion['zone'] . ' and ends on **' . $nextIncursion['end_time']->format('l jS \a\t ga') . '**.';

        $message->channel->sendMessage($reply);
    }
}