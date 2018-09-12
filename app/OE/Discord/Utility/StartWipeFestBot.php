<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 12/09/2018
 * Time: 10:01
 */

namespace App\OE\Discord\Utility;


use Discord\Parts\Channel\Message;

class StartWipeFestBot
{
    public function start(Message $message)
    {
        if (!$this->isLoggingStartedBySecrezyInTheLogChannel($message)) return;

        $reportId = $this->extractLogId($message->content);

        $message->channel->sendMessage("!wipefest listen --report {$reportId} --death-threshold 2");
    }

    /**
     * @param Message $message
     */
    private function isLoggingStartedBySecrezyInTheLogChannel(Message $message)
    {
        if ($message->channel_id !== config('operation-eskimo.discord-channel-logs')) return false;

        if ($message->author->username !== 'WCL') return false;

        if (!str_contains($message->content, 'secrezy')) return false;

        return true;
    }

    private function extractLogId($content)
    {
        foreach (explode("\n", $content) as $line) {
            preg_match('/\/([a-zA-Z0-9]+)\/$/', $line, $matches);

            if (count($matches) <= 0) continue;

            return $matches[1];
        }
    }
}