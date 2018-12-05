<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\WoW\HeartOfAzeroth;
use Discord\Parts\Channel\Message;

class HoAHallOfFame extends Command
{
    public function execute(Message $message)
    {
        $message->channel->sendMessage($this->generateMessage());
    }

    public function generateMessage() {
        $hallOfFame = HeartOfAzeroth::hallOfFame();
        $hallOfShame = HeartOfAzeroth::hallOfShame();

        $message = "!" . PHP_EOL . PHP_EOL . "**👑 👑 👑 Hall of Fame 👑 👑 👑 **" . PHP_EOL . PHP_EOL;

        $message .= $this->createHall($hallOfFame);

        $message .= PHP_EOL . "**💩 💩 💩 Hall of Shame 💩 💩 💩**" . PHP_EOL . PHP_EOL;

        $message .= $this->createHall($hallOfShame);

        return $message;
    }

    private function createHall($hall) {
        $message = "";

        $iteration = 1;
        foreach($hall as $hoa) {
            $message .= $this->formatCharactersHoaLevel($iteration, $hoa) . PHP_EOL;
            $iteration++;
        }

        return $message;
    }

    /**
     * @return string
     * @author Jeremy
     */
    private function formatCharactersHoaLevel($iteration, $hoa): string
    {
        $characterName = str_pad($hoa->character_name, 40, ' ', STR_PAD_RIGHT);

        return "{$iteration}. {$characterName} {$hoa->level} ({$hoa->getPercentageIntoLevel()}%)";
    }
}