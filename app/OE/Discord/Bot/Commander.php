<?php
namespace App\OE\Discord\Bot;

use App\OE\Discord\Bot\Commands\ListRecentLegendaryDrops;
use App\OE\Discord\Bot\Commands\ListCommands;
use Discord\Parts\Channel\Message;

class Commander
{
    private static $commands = [
        'commands' => ListCommands::class,
        'legendaries' => ListRecentLegendaryDrops::class
    ];

    public function execute(Message $message)
    {
        if( ! $this->isCommand($message) ) return;

        $command = $this->extractCommandName($message);

        if( ! self::commandExists($command) ) return;

        return $this->resolveCommand($command)->execute($message);
    }

    private function extractCommandName(Message $message)
    {
        return preg_replace('/[^a-z]/', '', strtolower($message->content));
    }

    private function isCommand(Message $message)
    {
        return starts_with($message->content, '!');
    }

    private function resolveCommand($command) : Command
    {
        return app(self::getCommands()[$command]);
    }

    public static function getCommands()
    {
        return static::$commands;
    }

    public static function commandExists($command)
    {
        return isset(self::getCommands()[$command]);
    }
}