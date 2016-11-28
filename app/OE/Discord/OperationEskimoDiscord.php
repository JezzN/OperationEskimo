<?php
namespace App\OE\Discord;

use Discord\Discord;
use Discord\Parts\Channel\Channel;

class OperationEskimoDiscord
{
    /** @var Discord */
    private $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public static function forServer(Discord $discord)
    {
        return new OperationEskimoDiscord($discord);
    }

    public function sendMessageToGeneralChat($message)
    {
        return $this->sendMessage(config('operation-eskimo.discord-channel-general'), $message);
    }

    public function sendMessageToOfficerChat($message)
    {
        return $this->sendMessage(config('operation-eskimo.discord-channel-officer'), $message);
    }

    public function sendMessageToTestChat($message)
    {
        return $this->sendMessage(config('operation-eskimo.discord-channel-test'), $message);
    }

    private function sendMessage($channelId, $message)
    {
        return $this->discord->factory(Channel::class, ['id' => $channelId])->sendMessage($message);
    }
}