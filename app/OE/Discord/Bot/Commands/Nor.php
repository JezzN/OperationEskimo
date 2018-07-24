<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class Nor extends Command
{

    public function execute(Message $message)
    {
        $message->channel->sendMessage("https://i.imgur.com/Yt1oP64.png");
    }
}