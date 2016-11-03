<?php

namespace App\Http\Controllers;

use App\OE\Forum\Link;
use App\OE\Loot\LootDrop;
use App\OE\Loot\LootStats;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;

use App\Http\Requests;

class LootController extends Controller
{
    /** @var LootStats */
    private $lootStats;

    public function __construct(LootStats $lootStats)
    {
        $this->lootStats = $lootStats;
    }

    public function index()
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->where('quality', '>=', 4)->paginate(15);

        return view('loot.index')
            ->withDrops($drops);
    }

    public function character($characterName)
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->where('quality', '>=', 4)->where('character_name', $characterName)->paginate(15);

        return view('loot.character')
            ->withDrops($drops)
            ->with('characterName', $characterName);
    }


    public function mythicPlus()
    {
        return view('loot.mythic-plus')
            ->with('mythicPlusLooters', $this->lootStats->mythicPlusLootCount())
            ->with('start', LootDrop::orderBy('loot_time', 'asc')->first());
    }

    public function mythicPlusCache()
    {
        return view('loot.mythic-plus-cache')
            ->with('cacheItems', $this->lootStats->weeklyCacheItems())
            ->with('raidersWithoutCache', $this->lootStats->withoutCacheReward());
    }

    public function legendary()
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->where('quality', '=', 5)->paginate(15);

        return view('loot.legendary')
            ->withDrops($drops);
    }
}
