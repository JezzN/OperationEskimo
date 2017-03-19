<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\Discord\Bot\Commander;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class ListCommands extends Command
{
    protected $description = 'List available commands';

    /** @var Commander */
    private $commander;

    public function __construct(Commander $commander)
    {
        $this->commander = $commander;
    }

    /**
     * Executes the commands, printing a list of all available commands to the channel.
     *
     * @author Jeremy
     * @param Message $message
     */
    public function execute(Message $message, Discord $discord)
    {
        $reply = "Command List:" . PHP_EOL . PHP_EOL;

        foreach( $this->commander->getCommands() as $commandName => $command ) {
            $description = $this->commander->resolveCommand($commandName)->getDescription();

            $reply .= "**!{$commandName}** - {$description}" . PHP_EOL;
        }

        $message->channel->sendMessage($reply);
    }
}