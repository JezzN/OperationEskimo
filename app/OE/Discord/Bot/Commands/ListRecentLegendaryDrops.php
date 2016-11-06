<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use App\OE\Loot\LootDrop;
use Carbon\Carbon;
use Discord\Parts\Channel\Message;

class ListRecentLegendaryDrops extends Command
{
    protected $description = 'List recent legendary drops';

    public function execute(Message $message)
    {
        $legendaries = LootDrop::where('quality', 5)->where('item_level', '>=', 895)->orderBy('loot_time', 'desc')->take(5)->get();

        $reply = "Most recent legendaries are:" . PHP_EOL . PHP_EOL;

        foreach( $legendaries as $legendary ) {
            $reply .= "**{$legendary->name}** dropped for **{$legendary->character_name}** at {$legendary->readableLootTime()}" . PHP_EOL . PHP_EOL;
        }

        $message->channel->sendMessage($reply);
    }
}