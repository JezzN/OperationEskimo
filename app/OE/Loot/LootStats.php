<?php
namespace App\OE\Loot;

use App\OE\OperationEskimo;
use Carbon\Carbon;

class LootStats
{
    private $raiders = [];

    public function __construct(OperationEskimo $operationEskimo)
    {
        $this->raiders = $operationEskimo->raiders()->pluck('character.character.name')->toArray();
    }

    public function mythicPlusLootCount()
    {
        $stats = [];

        $lootersWeekly = $this->lootBetween(Carbon::now()->subWeek(), Carbon::now());
        $lootersMonthly = $this->lootBetween(Carbon::now()->subMonth(), Carbon::now());

        foreach( $this->raiders as $raider ) {
            $stats[] = [
                'name' => $raider,
                'this_week' => isset($lootersWeekly[$raider]) ? $lootersWeekly[$raider] : 0,
                'this_month' => isset($lootersMonthly[$raider]) ? $lootersMonthly[$raider] : 0,
            ];
        }

        usort($stats, function($a, $b) {
            return $b['this_week'] > $a['this_week'];
        });

        return $stats;
    }

    private function lootBetween($from, $to)
    {
        return LootDrop::selectRaw('count(*) as count, character_name')
            ->whereBetween('loot_time', [$from, $to])
            ->where('context', 'LIKE', 'challenge-mode%')
            ->whereIn('character_name', $this->raiders)
            ->groupBy('character_name')
            ->orderBy('count', 'desc')
            ->get()
            ->pluck('count', 'character_name');
    }

    public function weeklyCacheItems()
    {
        $caches = LootDrop::where('context', 'challenge-mode-jackpot')->whereIn('character_name', $this->raiders)->get();

        return $caches->sort(function($a, $b) {
            return $b->getCacheLevel() > $a->getCacheLevel();
        });
    }

    public function withoutCacheReward()
    {
        $hasCache = $this->weeklyCacheItems()->pluck('character_name')->toArray();

        return array_diff($this->raiders, $hasCache);
    }
}