<?php
namespace App\OE\Loot;

use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Pwnraid\Bnet\Warcraft\Client;

class ItemLevelFetcher
{
    /** @var Repository */
    private $cache;

    /** @var Client */
    private $client;

    public function __construct(Client $client, Repository $cache)
    {
        $this->cache = $cache;
        $this->client = $client;
    }

    /**
     * Performs a lookup to get an item level for a specific item.
     *
     * @return mixed
     * @param       $itemId
     * @param array $bonusList
     * @author Jeremy
     */
    public function forItem($itemId, array $bonusList = [])
    {
        return $this->client->items()->find($itemId, $bonusList);
    }

    public function averageForCharacter($character)
    {
        $character = $character['character']['character'];

        return $this->cache->remember('item_level_' . $character['name'], Carbon::now()->addMinute(5), function() use ($character) {

            $character = $this->client->characters()->on($character['realm'])->find($character['name'], ['items']);

            return $character['items']['averageItemLevelEquipped'];
        });

    }

    private function createCacheKey($itemId, $bonusList)
    {
        return 'item_cache_' . $itemId . implode('', $bonusList);
    }
}