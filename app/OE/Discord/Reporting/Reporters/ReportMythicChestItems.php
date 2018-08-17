<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Loot\LootDrop;
use App\OE\mo;
use Carbon\Carbon;
use Discord\Discord;
use Illuminate\Database\DatabaseManager;

class ReportMythicChestItems extends AbstractDatabaseChangeReporter
{
    /** @var OperationEskimo */
    private $oe;

    /**
     * ReportMythicChestItems constructor.
     *
     * @param DatabaseManager $db
     * @param OperationEskimo $oe
     */
    public function __construct(DatabaseManager $db, OperationEskimo $oe)
    {
        parent::__construct($db);
        $this->oe = $oe;
    }

    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $items = $this->getNewRecords(LootDrop::where('context', 'challenge-mode-jackpot')->where('item_level', '>=', 930)->where('loot_time', '>', Carbon::now()->subDay())->orderBy('loot_time', 'asc'));

        $raiderNames = $this->oe->raiders()->pluck('character_name');

        foreach( $items as $item ) {

            if( ! $raiderNames->contains($item->character_name) ) continue;

            $oeDiscord->sendMessageToGeneralChat("**{$item->character_name}** looted **{$item->name} ({$item->item_level})** from their mythic chest!");
        }
    }
}