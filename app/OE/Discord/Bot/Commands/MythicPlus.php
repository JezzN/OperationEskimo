<?php

namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\Loot\LootStats;
use App\OE\OperationEskimo;
use App\OE\WoW\MythicLeaderboard;
use Discord\Parts\Channel\Message;

class MythicPlus extends Command
{
    /** @var LootStats */
    private $lootStats;
    /** @var OperationEskimo */
    private $operationEskimo;

    protected $description = "Find out who has yet to complete the minimum key this reset";

    public function __construct(LootStats $lootStats, OperationEskimo $operationEskimo)
    {
        $this->lootStats = $lootStats;
        $this->operationEskimo = $operationEskimo;
    }

    public function execute(Message $message)
    {
        $message->channel->sendMessage($this->generateMessage());
    }

    public function generateMessage() {
        $completed = MythicLeaderboard::whereBetween('completed_at', $this->lootStats->thisWeek())->where('keystone_level', '>=', 10)->get()->pluck('character_name')->unique()->sort();

        $notCompleted = $this->operationEskimo->raiders()->pluck('character_name')->diff($completed)->sort();

        $reply = "Mythic Cache this reset: " . PHP_EOL . PHP_EOL . "**Complete:**" . PHP_EOL . PHP_EOL;

        foreach ($completed as $complete) {
            $this->addPlayerToReply($complete, $reply);
        }

        $reply .= PHP_EOL . PHP_EOL . "**Incomplete:**" . PHP_EOL . PHP_EOL;

        foreach ($notCompleted as $complete) {
            $this->addPlayerToReply($complete, $reply);
        }

        return $reply;
    }

    private function addPlayerToReply($player, &$reply)
    {
        $highest = MythicLeaderboard::whereBetween('completed_at', $this->lootStats->thisWeek())->where('character_name', $player)->orderBy('keystone_level', 'desc')->first();

        $level = $highest ? $highest->keystone_level : "0";

        $reply .= $player . " ({$level})" .PHP_EOL;
    }
}