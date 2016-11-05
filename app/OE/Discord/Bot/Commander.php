<?php
namespace App\OE\Discord\Bot;

use App\OE\Discord\Bot\Commands\LegendaryDropsCommand;
use Discord\Parts\Channel\Message;

class Commander
{
    private $commands = [
        'legendaries' => LegendaryDropsCommand::class
    ];

    public function execute(Message $message)
    {
        if( ! $this->isCommand($message) ) return;

        $command = $this->extractCommandName($message);

        if( ! isset($this->commands[$command]) ) return;

        return app($this->commands[$command])->execute($message);
    }

    private function extractCommandName(Message $message)
    {
        return preg_replace('/[^a-z]/', '', strtolower($message->content));
    }

    private function isCommand(Message $message)
    {
        return starts_with($message->content, '!');
    }
}