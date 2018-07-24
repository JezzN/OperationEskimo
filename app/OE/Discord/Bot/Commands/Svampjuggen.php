<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class Svampjuggen extends Command
{

    public function execute(Message $message)
    {
        $message->channel->sendMessage("https://i.imgur.com/Yjjt0XF.png");
    }
}