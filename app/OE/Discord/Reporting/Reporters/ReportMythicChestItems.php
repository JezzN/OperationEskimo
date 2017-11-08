<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Loot\LootDrop;
use Carbon\Carbon;
use Discord\Discord;

class ReportMythicChestItems extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $items = $this->getNewRecords(LootDrop::where('context', 'challenge-mode-jackpot')->where('item_level', '>=', 930)->where('loot_time', '>', Carbon::now()->subDay())->orderBy('loot_time', 'asc'));

        foreach( $items as $item ) {
            $oeDiscord->sendMessageToGeneralChat("**{$item->character_name}** looted **{$item->name} ({$item->item_level})** from their mythic chest!");
        }
    }
}