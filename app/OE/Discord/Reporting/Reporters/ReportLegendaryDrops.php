<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Loot\LootDrop;
use Discord\Discord;

class ReportLegendaryDrops extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $legendaries = $this->getNewRecords(LootDrop::where('quality', 5)->where('item_level', '>=', 895)->orderBy('loot_time', 'asc'));

        foreach( $legendaries as $legendary ) {
            $oeDiscord->sendMessageToGeneralChat("**{$legendary->character_name}** looted legendary item **{$legendary->name}**!");
        }
    }
}