<?php

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WoW\MythicLeaderboard;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Pwnraid\Bnet\Warcraft\Client;

class ImportMythicPlusLeaderboards extends Command
{
    protected $signature = 'mythic-plus:leaderboards';

    protected $description = 'Import the mythic plus leaderboards';

    const CONNECTED_REALM_ID = 3682;

    /** @var Client */
    private $client;
    /** @var OperationEskimo */
    private $operationEskimo;

    private $raiders;

    public function __construct(Client $client, OperationEskimo $operationEskimo)
    {
        $this->client = $client;
        parent::__construct();
        $this->operationEskimo = $operationEskimo;
    }

    public function handle()
    {
        $dungeons = $this->getCurrentLeaderBoards();
        $currentPeriod = $this->getCurrentPeriod();
        $this->raiders = $this->operationEskimo->raiders()->pluck('character_name');

        foreach ($dungeons as $dungeon) {
            $leaderBoard = $this->getLeaderBoard($dungeon->id, $currentPeriod);

            foreach ($leaderBoard->leading_groups as $run) {

                foreach ($run->members as $member) {
                    if (!$this->isRaider($member)) continue;
                    $this->addRun($dungeon->name, $run);
                }
            }
        }
    }

    private function addRun($dungeonName, $run) {
        $runId = (string) $run->completed_timestamp;
        $level = $run->keystone_level;
        $timeTaken = $run->duration;
        $time = Carbon::createFromTimestamp($run->completed_timestamp/1000);

        if (MythicLeaderboard::where('run_id', $runId)->first()) return;

        foreach ($run->members as $member) {
            if (!$this->isRaider($member)) continue;

            $mythicLeaderboard = new MythicLeaderboard();
            $mythicLeaderboard->character_name = $member->profile->name;
            $mythicLeaderboard->keystone_level = $level;
            $mythicLeaderboard->run_id = $runId;
            $mythicLeaderboard->completed_at = $time;
            $mythicLeaderboard->time_taken = $timeTaken;
            $mythicLeaderboard->dungeon_name = $dungeonName;
            $mythicLeaderboard->save();
        }
    }

    private function isRaider($member) {
        return in_array($member->profile->name, $this->raiders->toArray());
    }
    private function getLeaderBoard($dungeonId, $period) {
        return $this->query("https://eu.api.battle.net/data/wow/connected-realm/" . self::CONNECTED_REALM_ID . "/mythic-leaderboard/{$dungeonId}/period/{$period}?namespace=dynamic-eu&locale=en_GB");
    }

    private function getCurrentPeriod() {
        return $this->query("https://eu.api.battle.net/data/wow/mythic-challenge-mode/?namespace=dynamic-eu&locale=en_GB")->current_period;
    }

    private function getCurrentLeaderBoards() {
        return $this->query("https://eu.api.battle.net/data/wow/connected-realm/" . self::CONNECTED_REALM_ID . "/mythic-leaderboard/?namespace=dynamic-eu&locale=en_GB")->current_leaderboards;
    }

    private function query($url) {
        $accessToken = config('operation-eskimo.access-token');

        return json_decode(file_get_contents("{$url}&access_token={$accessToken}"));
    }
}