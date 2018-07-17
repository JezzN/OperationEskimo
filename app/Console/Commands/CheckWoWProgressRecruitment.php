<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 17/07/2018
 * Time: 08:45
 */

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WowProgress\Character;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckWoWProgressRecruitment extends Command
{
    protected $signature = 'wowprogress:check';

    protected $description = 'Import wowprogress recruitment status';

    /**
     * @var OperationEskimo
     */
    private $operationEskimo;

    public function __construct(OperationEskimo $operationEskimo)
    {
        parent::__construct();
        $this->operationEskimo = $operationEskimo;
    }

    public function handle()
    {
        foreach ($this->operationEskimo->raiders() as $raider) {
            $characterName = $raider->character_name;
            $url = "https://www.wowprogress.com/character/eu/ragnaros/{$characterName}";

            $wowprogress = file_get_contents($url);



            if (str_contains($wowprogress, 'Yes, ready to transfer') || str_contains($wowprogress, 'Yes, without transfer')) {
                preg_match('/aria-label="([a-zA-Z0-9 :,]+)"/', $wowprogress, $matches);
                $lastUpdated = $matches[1];
                $lastUpdated = Carbon::createFromFormat('M j, Y H:i', $lastUpdated);

                if (Character::where('last_updated', $lastUpdated)->where('url', $url)->count()) continue;

                $character = new Character();
                $character->character_name = $characterName;
                $character->url = $url;
                $character->last_updated = $lastUpdated;
                $character->save();
            }
        }
    }
}