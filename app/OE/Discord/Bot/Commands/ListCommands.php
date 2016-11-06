<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class ListCommands extends Command
{
    protected $description = 'List available commands';

    public function execute(Message $message)
    {
        $reply = "Available command list:" . PHP_EOL . PHP_EOL;

        foreach( $this->commands as $command => $object ) {
            $description = app($object)->getDescription();

            $reply .= "**!{$command}** - {$description}" . PHP_EOL . PHP_EOL;
        }

        $message->channel->sendMessage($reply);
    }
}