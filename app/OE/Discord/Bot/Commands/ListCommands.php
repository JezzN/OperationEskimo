<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\Discord\Bot\Commander;
use Discord\Parts\Channel\Message;

class ListCommands extends Command
{
    protected $description = 'List available commands';

    public function execute(Message $message)
    {
        $reply = "Command List:" . PHP_EOL . PHP_EOL;

        foreach( Commander::getCommands() as $commandName => $command ) {
            $description = Commander::resolveCommand($commandName)->getDescription();

            $reply .= "**!{$commandName}** - {$description}" . PHP_EOL;
        }

        $message->channel->sendMessage($reply);
    }
}