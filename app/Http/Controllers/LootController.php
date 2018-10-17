<?php

namespace App\Http\Controllers;

use App\OE\Forum\Link;
use App\OE\Loot\LootDrop;
use App\OE\Loot\LootStats;
use App\OE\WoW\MythicLeaderboard;
use App\OE\WoW\MythicPlusCompletion;
use Carbon\Carbon;
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
        $completionsThisReset = $this->flattenCompletions(MythicLeaderboard::whereBetween('completed_at', $this->lootStats->thisWeek())->get());
        $completionsThisMonth = $this->flattenCompletions(MythicLeaderboard::whereBetween('completed_at', [Carbon::now()->subMonth(), Carbon::now()])->get());
        $completionsAllTime =  $this->flattenCompletions(MythicLeaderboard::whereNotNull('completed_at')->get());

        return view('loot.mythic-plus')
            ->with('completionsThisReset', $completionsThisReset)
            ->with('completionsThisMonth', $completionsThisMonth)
            ->with('completionsAllTime', $completionsAllTime);
    }

    private function flattenCompletions($completions) {
        $result = [];

        foreach ($completions as $completion) {
            if ($completion->is_initial_recording) continue;
            if (!array_key_exists($completion->character_name, $result)) {
                $result[$completion->character_name] = [];
                $result[$completion->character_name]['total'] = 0;
            }

            $result[$completion->character_name]['total']++;
        }

        return collect($result)->sortByDesc('total');
    }

    public function mythicPlusCache()
    {
        return view('loot.mythic-plus-cache')
            ->with('cacheItems', $this->lootStats->weeklyCacheItems())
            ->with('raidersWithoutCache', $this->lootStats->withoutCacheReward());
    }

    public function mythicRaid()
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->where('context', '=', 'raid-mythic')->where('item_level', '>=', 300)->paginate(40);

        return view('loot.mythic-raids')
            ->withDrops($drops);
    }
}
