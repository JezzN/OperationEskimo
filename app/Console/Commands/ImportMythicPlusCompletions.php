<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 17/07/2018
 * Time: 08:45
 */

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WoW\MythicPlusCompletion;
use App\OE\WowProgress\Character;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Pwnraid\Bnet\Warcraft\Characters\AchievementEntity;
use Pwnraid\Bnet\Warcraft\Client;

class ImportMythicPlusCompletions extends Command
{
    protected $signature = 'mythic-plus:completions';

    protected $description = 'Import the mythic plus completed by raiders';

    const MYTHIC_PLUS_2 = 33096;
    const MYTHIC_PLUS_5 = 33097;
    const MYTHIC_PLUS_10 = 33098;
    const MYTHIC_PLUS_15 = 32028;

    /**
     * @var OperationEskimo
     */
    private $operationEskimo;
    /** @var Client */
    private $client;

    public function __construct(OperationEskimo $operationEskimo, Client $client)
    {
        parent::__construct();
        $this->operationEskimo = $operationEskimo;
        $this->client = $client;
    }

    public function handle()
    {
        foreach ($this->operationEskimo->raiders() as $character) {
            $character = $this->client->characters()->on('Ragnaros')->find($character->character_name, ['achievements']);

            $plus2Completions = $this->findCompletionsForCriteria(self::MYTHIC_PLUS_2, $character);
            $plus5Completions = $this->findCompletionsForCriteria(self::MYTHIC_PLUS_5, $character);
            $plus10Completions = $this->findCompletionsForCriteria(self::MYTHIC_PLUS_10, $character);
            $plus15Completions = $this->findCompletionsForCriteria(self::MYTHIC_PLUS_15, $character);

            $previousCompletion = MythicPlusCompletion::where('character_name',  $character->name)->orderBy('created_at', 'desc')->first();

            $completion = new MythicPlusCompletion();
            $completion->character_name = $character->name;

            if ($previousCompletion) {
                $completion->plus_2 = $plus2Completions -  (MythicPlusCompletion::where('character_name',  $character->name)->sum('plus_2'));
                $completion->plus_5 = $plus5Completions - (MythicPlusCompletion::where('character_name',  $character->name)->sum('plus_5'));
                $completion->plus_10 = $plus10Completions - (MythicPlusCompletion::where('character_name',  $character->name)->sum('plus_10'));
                $completion->plus_15 = $plus15Completions - (MythicPlusCompletion::where('character_name',  $character->name)->sum('plus_15'));
            } else {
                $completion->plus_2 = $plus2Completions;
                $completion->plus_5 = $plus5Completions;
                $completion->plus_10 = $plus10Completions;
                $completion->plus_15 = $plus15Completions;
            }

            $completion->is_initial_recording = ($previousCompletion == null);
            $completion->save();
        }
    }

    private function findCompletionsForCriteria($criteria, $character) {
        $criteriaIndex = array_search($criteria, $character['achievements']['criteria']);

        return $character['achievements']['criteriaQuantity'][$criteriaIndex];
    }
}