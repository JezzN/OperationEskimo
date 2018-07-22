<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\OperationEskimo;
use Discord\Parts\Channel\Message;

class Trials extends Command
{
    /** @var OperationEskimo */
    private $operationEskimo;

    public function __construct(OperationEskimo $operationEskimo)
    {
        $this->operationEskimo = $operationEskimo;
    }

    public function execute(Message $message)
    {
        $reply = "Current trials are:" . PHP_EOL . PHP_EOL;

        foreach($this->operationEskimo->raiders() as $raider) {
            if ($raider->rankName() === 'Trial') {
                $reply .= $raider->character_name . PHP_EOL;
            }
        }

        $message->channel->sendMessage($reply);
    }
}