<?php namespace App\OE\Discord\Bot;

use Discord\Discord;
use Discord\Parts\Channel\Message;

abstract class Command
{
    protected $description = '';

    public abstract function execute(Message $message, Discord $discord);

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
}