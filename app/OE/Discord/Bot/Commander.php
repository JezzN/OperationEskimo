<?php
namespace App\OE\Discord\Bot;

use App\OE\Discord\Bot\Commands\SimpleCommand;
use Discord\Parts\Channel\Message;

class Commander
{
    /**
     * @var array
     */
    private $commands;

    /**
     * Register commands with the commander.
     *
     * Commander constructor.
     * @param Command[]
     */
    public function register(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * Determines if the message is a command and if it is, will execute the required command.
     *
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
                $this->addSimpleCommand($message, $command);
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
        return preg_replace('/[^a-z0-9A-Z]/', '', strtolower($message->content));
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
        if (starts_with($command, 'dangerzone')) {
            $command = 'dangerzone';
        }

        if (starts_with($command, 'remove')) {
            $command = 'remove';
        }

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
        if (starts_with($command, 'dangerzone')) {
            return true;
        }

        if (starts_with($command, 'remove')) {
            return true;
        }

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