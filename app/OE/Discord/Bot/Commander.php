<?php
namespace App\OE\Discord\Bot;

use App\OE\Discord\Bot\Commands\Dangerzone;
use App\OE\Discord\Bot\Commands\ListRecentLegendaryDrops;
use App\OE\Discord\Bot\Commands\ListCommands;
use App\OE\Discord\Bot\Commands\ListTurds;
use App\OE\Discord\Bot\Commands\Nor;
use App\OE\Discord\Bot\Commands\PlayFile;
use App\OE\Discord\Bot\Commands\Spot;
use App\OE\Discord\Bot\Commands\Svampjuggen;
use App\OE\Discord\Bot\Commands\Trials;
use Discord\Parts\Channel\Message;

class Commander
{
    private $commands = [
        'commands' => ListCommands::class,
        'legendaries' => ListRecentLegendaryDrops::class,
        'dangerzone' => Dangerzone::class,
        'spot' => Spot::class,
        'trials' => Trials::class,
        'nor' => Nor::class,
        'bssnies' => Svampjuggen::class
    ];

    /**
     * Determines if the message is a command and if it is, will execute the required command.
     *
     * @author Jeremy
     * @param Message $message
     */
    public function execute(Message $message)
    {
        if( ! $this->isCommand($message) ) return;

        $command = $this->extractCommandName($message);

        if( ! $this->commandExists($command) ) return;

        return $this->resolveCommand($command)->execute($message);
    }

    /**
     * Extract the name of the command from the message content.
     *
     * A command will begin with an exclamation mark, for example !legendaries.
     *
     * @return mixed
     * @param Message $message
     * @author Jeremy
     */
    private function extractCommandName(Message $message)
    {
        return preg_replace('/[^a-z]/', '', strtolower($message->content));
    }

    /**
     * Returns TRUE if the message content looks like a command.
     *
     * @return bool
     * @param Message $message
     * @author Jeremy
     */
    private function isCommand(Message $message)
    {
        return starts_with($message->content, '!');
    }

    /**
     * Resolve a command.
     *
     * @return Command
     * @param $command
     * @author Jeremy
     */
    public function resolveCommand($command) : Command
    {
        return app($this->getCommands()[$command]);
    }

    /**
     * Get the current set of commands.
     *
     * @return array
     * @author Jeremy
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Check if a command exists.
     *
     * @return bool
     * @param $command
     * @author Jeremy
     */
    public function commandExists($command)
    {
        return isset($this->getCommands()[$command]);
    }
}