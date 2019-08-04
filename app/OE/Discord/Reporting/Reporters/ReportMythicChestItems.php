<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Loot\LootDrop;
use App\OE\mo;
use App\OE\OperationEskimo;
use Carbon\Carbon;
use Discord\Discord;
use Illuminate\Database\DatabaseManager;

class ReportMythicChestItems extends AbstractDatabaseChangeReporter
{
    /** @var OperationEskimo */
    private $oe;

    private static $reported = [];

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

        $items = $this->getNewRecords(LootDrop::where('context', 'challenge-mode-jackpot')->where('item_level', '>=', 380)->where('loot_time', '>', Carbon::now()->subDay())->orderBy('loot_time', 'asc'));

        $raiderNames = $this->oe->raiders()->pluck('character_name');

        foreach( $items as $item ) {

            if( ! $raiderNames->contains($item->character_name) ) continue;

            if (in_array($item->id, static::$reported)) {
                return;
            }

            static::$reported[] = $item->id;

            if (empty($item->item_slot) || !in_array($item->item_slot, [2,3,5])) {
                $oeDiscord->sendMessageToLootChannel("**{$item->character_name}** looted **{$item->name} ({$item->item_level})** from their mythic chest!");
            } else {
                $oeDiscord->sendMessageToLootChannel("**{$item->character_name}** discovered **{$item->name} ({$item->item_level})** from the Titan Residuum vendor!");
            }
        }
    }
}