<?php
/**
 * Created by PhpStorm.
 * User: Jezz
 * Date: 18/02/2019
 * Time: 12:34
 */

namespace App\OE\Discord\Bot\Commands;


use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class RemoveSimpleCommand extends Command
{
    protected $description = "Remove a custom command";

    public function execute(Message $message)
    {
        if (!$this->cameFromOfficerChat($message)) {
            return $message->reply("Officers only!");
        }

        $commandToRemove = str_replace('!remove ', '', $message->content);

        $simpleCommand = SimpleCommand::where('name', $commandToRemove)->first();

        if (!$simpleCommand) {
            return $message->reply("{$commandToRemove->name} does not exist");
        }

        $simpleCommand->delete();
        $message->reply("{$simpleCommand->name} has been removed");
    }
}