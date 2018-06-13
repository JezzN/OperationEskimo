<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class Spot extends Command
{

    public function execute(Message $message)
    {
        $message->channel->sendMessage("https://www.youtube.com/watch?v=hgatkabZe6A");
    }
}