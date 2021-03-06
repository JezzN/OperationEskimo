<?php
namespace App\OE\Loot;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class LootDrop extends Model
{
    public function readableLootTime()
    {
        return (new Carbon($this->loot_time))->format('H:i M jS');
    }

    public static function createFromLootEvent($event) : LootDrop
    {
        $lootDrop = new LootDrop();
        $lootDrop->loot_time = Carbon::createFromTimestamp($event['timestamp']/1000);
        $lootDrop->raw_time = (string) $event['timestamp'];
        $lootDrop->character_name = $event['character'];
        $lootDrop->item_id = $event['itemId'];
        $lootDrop->context = $event['context'];
        $lootDrop->bonus_list = json_encode($event['bonusLists']);
        $lootDrop->item_level = $event['itemLevel'];
        $lootDrop->quality = $event['quality'];
        $lootDrop->name = $event['name'];
        $lootDrop->equippable = $event['equippable'];
        $lootDrop->required_character_level = $event['requiredLevel'];
        $lootDrop->has_sockets = $event['hasSockets'];
        $lootDrop->tooltip_description = $event['nameDescription'];
        $lootDrop->tooltip_color = $event['nameDescriptionColor'];
        $lootDrop->heroic_tooltip = $event['heroicTooltip'];
        $lootDrop->unique_identifier = $event['unique_identifier'];

        return $lootDrop;
    }

    public function tooltip()
    {
        $bonus = implode(':', json_decode($this->bonus_list));

        return "<a href=\"//www.wowhead.com/item={$this->item_id}\" rel=\"bonus={$bonus}\">{$this->name}</a>";
    }

    public function getCacheLevel() : int
    {
        if( $this->context !== 'challenge-mode-jackpot' ) {
            throw new LogicException("This item is not from a cache");
        }

        preg_match('/[0-9]{1,2}/', $this->tooltip_description, $matches);
        if ( isset($matches[0]) ) {
            return  $matches[0];
        }

        return $this->guessCacheLevel();
    }

    private function guessCacheLevel()
    {
        if( $this->item_level >= 885 ) return 12;

        if( $this->item_level >= 880 ) return 10;

        return 0;
    }

    public function scopeLegendary($q)
    {
        return $q->where('quality', '=', 5)->where('item_level', '>=', 895);
    }
}