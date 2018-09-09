<?php

namespace App\Http\Controllers;

use App\OE\Forum\Link;
use App\OE\Loot\LootDrop;
use App\OE\Loot\LootStats;
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
        $completionsThisReset = $this->flattenCompletions(MythicPlusCompletion::whereBetween('created_at', $this->lootStats->thisWeek())->where('is_initial_recording', false)->get());
        $completionsThisMonth =  $this->flattenCompletions(MythicPlusCompletion::whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->where('is_initial_recording', false)->get());
        $completionsAllTime =  $this->flattenCompletions(MythicPlusCompletion::where('is_initial_recording', false)->get());

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
                $result[$completion->character_name]['plus_2'] = 0;
                $result[$completion->character_name]['plus_5'] = 0;
                $result[$completion->character_name]['plus_10'] = 0;
                $result[$completion->character_name]['plus_15'] = 0;
            }

            $result[$completion->character_name]['plus_2'] += $completion->plus_2;
            $result[$completion->character_name]['plus_5'] += $completion->plus_5;
            $result[$completion->character_name]['plus_10'] += $completion->plus_10;
            $result[$completion->character_name]['plus_15'] += $completion->plus_15;
        }

        foreach ($result as $i => $r) {
            $result[$i]['total'] = $result[$i]['plus_2'];
            $result[$i]['plus_2'] = ($result[$i]['plus_2'] - $result[$i]['plus_15'] - $result[$i]['plus_10'] - $result[$i]['plus_5']);
            $result[$i]['plus_5'] = ($result[$i]['plus_5'] - $result[$i]['plus_15'] - $result[$i]['plus_10']);
            $result[$i]['plus_10'] = ($result[$i]['plus_10'] - $result[$i]['plus_15']);
        }

        return collect($result)->sortByDesc('total');
    }

    public function mythicPlusCache()
    {
        return view('loot.mythic-plus-cache')
            ->with('cacheItems', $this->lootStats->weeklyCacheItems())
            ->with('raidersWithoutCache', $this->lootStats->withoutCacheReward());
    }

    public function legendary()
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->legendary()->paginate(15);

        $legendaries = LootDrop::legendary()->orderBy('loot_time', 'asc')->get();

        $stats = [];

        foreach( $legendaries as $drop ) {
            $date = new Carbon($drop->loot_time);
            $weekOfYear = $date->weekOfYear >= 10 ? $date->weekOfYear : "0{$date->weekOfYear}";
            $date = "{$date->year}W{$weekOfYear}";

            if( !isset($stats[$date]) ) $stats[$date] = 0;

            $stats[$date]++;
        }

        $stats2 = [];

        foreach( $stats as $year => $stat ) {
            $stats2[] = [
              'week' => (new Carbon($year))->format('Y-m-d'),
                'count' => $stat
            ];
        }

        return view('loot.legendary')
            ->withDrops($drops)
            ->withStats($stats2);
    }

    public function mythicRaid()
    {
        $drops = LootDrop::orderBy('loot_time', 'desc')->where('context', '=', 'raid-mythic')->where('item_level', '>=', 880)->paginate(40);

        return view('loot.mythic-raids')
            ->withDrops($drops);
    }
}
