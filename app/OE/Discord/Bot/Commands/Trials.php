<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\OperationEskimo;
use Discord\Parts\Channel\Message;

class Trials extends Command
{
    /** @var OperationEskimo */
    private $operationEskimo;

    protected $description = "List all trial members currently in the guild";

    public function __construct(OperationEskimo $operationEskimo)
    {
        $this->operationEskimo = $operationEskimo;
    }

    public function execute(Message $message)
    {
        $trials = $this->getTrials();

        if (empty($trials)) {
            return $message->channel->sendMessage("There are no trials currently.");
        }

        $reply = "Current trials are:" . PHP_EOL . PHP_EOL;

        foreach($trials as $trial) {
            $reply .= $trial->character_name . PHP_EOL;
        }

        $message->channel->sendMessage($reply);
    }

    private function getTrials() {
        $trials = [];

        foreach($this->operationEskimo->raiders() as $raider) {
            if ($raider->rankName() === 'Trial') {
               $trials[] = $raider;
            }
        }

        return $trials;
    }
}