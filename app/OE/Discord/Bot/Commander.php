<?php
namespace App\OE\Discord\Bot;

use App\OE\Discord\Bot\Commands\Dangerzone;
use App\OE\Discord\Bot\Commands\HoAHallOfFame;
use App\OE\Discord\Bot\Commands\Incursion;
use App\OE\Discord\Bot\Commands\IncursionCommand;
use App\OE\Discord\Bot\Commands\ListRecentLegendaryDrops;
use App\OE\Discord\Bot\Commands\ListCommands;
use App\OE\Discord\Bot\Commands\ListTurds;
use App\OE\Discord\Bot\Commands\MythicPlus;
use App\OE\Discord\Bot\Commands\Nor;
use App\OE\Discord\Bot\Commands\PlayFile;
use App\OE\Discord\Bot\Commands\SimpleCommand;
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
        'trials' => Trials::class,
        'mythicplus' => MythicPlus::class,
        'hof' => HoAHallOfFame::class,
        'incursion' => IncursionCommand::class
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

        if( ! $this->commandExists($command) ) {
            $simpleCommand = $this->findSimpleCommand($command);

            if ($simpleCommand) {
                $message->channel->sendMessage($simpleCommand->response);
            } else {
                if ($message->channel->id == '471923279507226624') {
                    $this->addSimpleCommand($message, $command);
                }
            }

            return;
        }

        return $this->resolveCommand($command)->execute($message);
    }

    private function findSimpleCommand($name) {
        return SimpleCommand::where('name', $name)->first();
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

    private function addSimpleCommand(Message $message)
    {
        preg_match("/(![^ ]+) (.+)/", $message->content, $matches);

        if (count($matches) < 3) {
            $message->reply("Unable to add command");
            return;
        }

        $command = $matches[1];
        $content = $matches[2];

        $simpleCommand = new SimpleCommand();
        $simpleCommand->name = str_replace('!', '', $command);
        $simpleCommand->response = $content;
        $simpleCommand->created_by = $message->author->username;
        $simpleCommand->save();

        $message->channel->sendMessage("{$simpleCommand->created_by} created {$command} command");
    }
}