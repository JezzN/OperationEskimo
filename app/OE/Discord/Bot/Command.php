<?php namespace App\OE\Discord\Bot;

use Discord\Discord;
use Discord\Parts\Channel\Message;

abstract class Command
{
    protected $description = '';

    public abstract function execute(Message $message);

    /**
     * Return the description of the command.
     *
     * @return string
     * @author Jeremy
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function cameFromOfficerChat(Message $message) : bool {
        return $message->channel->id == config('operation-eskimo.discord-channel-officer');
    }
}