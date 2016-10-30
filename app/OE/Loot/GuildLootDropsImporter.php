<?php
namespace App\OE\Loot;

use App\OE\Loot\ItemLevelFetcher;
use App\OE\Loot\LootDrop;
use App\OE\OperationEskimo;
use Carbon\Carbon;
use Pwnraid\Bnet\Warcraft\Characters\CharacterRequest;
use Pwnraid\Bnet\Warcraft\Client;
use Pwnraid\Bnet\Warcraft\Guilds\GuildRequest;

class GuildLootDropsImporter
{
    /** @var GuildRequest */
    private $guilds;

    /** @var OperationEskimo */
    private $operationEskimo;

    /** @var ItemLevelFetcher */
    private $itemLevelFetcher;
    /** @var Client */
    private $client;
    /** @var CharacterRequest */
    private $characters;

    public function __construct(GuildRequest $guilds, OperationEskimo $operationEskimo, ItemLevelFetcher $itemLevelFetcher, Client $client,  CharacterRequest $characters)
    {
        $this->guilds = $guilds;
        $this->operationEskimo = $operationEskimo;
        $this->itemLevelFetcher = $itemLevelFetcher;
        $this->client = $client;
        $this->characters = $characters;
    }

    /**
     * Generate a list of loot received by the guild.
     *
     * @return array
     * @author Jeremy
     */
    public function import()
    {
        $this->importLootFromCharacterFeeds();

        $this->importLootFromGuildFeed();
    }

    /**
     *
     * @return array|mixed
     * @param $event
     * @author Jeremy
     */
    private function isPendingImport($event)
    {
        $lootTimingMarginForError = (3600 * 3 * 1000);

        return LootDrop::where('character_name', $event['character'])->whereBetween('raw_time', [$event['timestamp'] - $lootTimingMarginForError, $event['timestamp'] + $lootTimingMarginForError])->where('item_id', $event['itemId'])->count() <= 0;
    }

    private function getItemDetails($itemId, $bonusList)
    {
        return $this->client->items()->find($itemId, $bonusList);
    }

    /**
     * Inspects the event type to work out if this is an item drop event.
     *
     * @return bool
     * @param $event
     * @author Jeremy
     */
    private function isLootEvent($event)
    {
        return $event['type'] === 'LOOT' || $event['type'] === 'itemLoot';
    }

    private function importLootFromGuildFeed()
    {
        $events = $this->guilds->find(config('services.battle-net.guild-name'), ['news'])->news;

        foreach ($events as $event) {
            $this->importEvent($event);
        }
    }

    private function importLootFromCharacterFeeds()
    {
        $raiders = $this->operationEskimo->raiders()->pluck('character.character.name');

        foreach ($raiders as $raider) {
            $character = $this->characters->find($raider, ['feed']);

            foreach ($character->feed as $event) {

                $event['character'] = $raider;

                $this->importEvent($event);
            }
        }
    }

    private function importEvent($event)
    {
        if (!$this->isLootEvent($event)) {
            return;
        }

        if (!$this->isPendingImport($event)) {
            return;
        }

        $event = $event + $this->getItemDetails($event['itemId'], $event['bonusLists'])->jsonSerialize();

        LootDrop::createFromLootEvent($event)->save();
    }
}