<?php
namespace App\OE\WarcraftLogs;

use Carbon\Carbon;
use GuzzleHttp\Client;

class WarcraftLogs
{
    protected $zones = [
        'Emerald Nightmare' => 10,
        'Trial of Valor'    => 12,
//        'The Nighthold'     => 11
    ];

    protected $encounters = [
        'Nythendra' => 1853,
        'Il\'gynoth, Heart of Corruption' => 1873,
        'Elerethe Renferal' => 1876,
        'Ursoc' => 1841,
        'Dragons of Nightmare' => 1854,
        'Cenarius' => 1877,
        'Xavius' => 1864,
        'Skorpyron' => 1849,
        'Chronomatic Anomaly' => 1865,
        'Spellblade Aluriel' => 1871,
        'Tichondrius' => 1862,
        'Star Augur Etraeus' => 1863,
        'Krosus' => 1842,
        'High Botanist Tel\'arn' => 1886,
        'Grand Magistrix Elisande' => 1872,
        'Gul\'dan' => 1866,
        'Odyn' => 1958,
        'Guarm' => 1962,
        'Helya' => 2008,
    ];

    private $metrics = [
        'dps', 'hps'
    ];

    protected $difficulties = [
        'mythic' => 5,
        'heroic' => 4,
        'normal' => 3
    ];

    /** @var Client */
    private $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function getRankings($characterName)
    {
        $rankings = [];

        foreach( $this->zones as $name => $zoneId ) {
            foreach( $this->metrics as $metric ) {
                foreach( $this->fetchRankings($characterName, $metric, $zoneId) as $ranking ) {
                    if( ! in_array($ranking->difficulty, $this->difficulties) ) continue;

                    $rankings[] = $this->parseRanking($ranking, $metric, $zoneId, $characterName);
                }
            }
        }

        return $rankings;
    }

    private function fetchRankings($characterName, $metric, $zone)
    {
        $realm = ucfirst(config('services.battle-net.realm'));
        $url = "https://www.warcraftlogs.com/v1/rankings/character/{$characterName}/{$realm}/EU?metric={$metric}&zone={$zone}&api_key=" . config('services.warcraft-logs.api-key');
        $response = $this->http->get($url);
        return json_decode($response->getBody()->getContents());
    }

    private function parseRanking($ranking, $metric, $zone, $characterName)
    {
        $record = new Ranking();
        $record->fight_start_time = Carbon::createFromTimestamp(round($ranking->startTime / 1000));
        $record->fight_start_time_raw = $ranking->startTime;
        $record->zone = array_search($zone, $this->zones);
        $record->encounter = array_search($ranking->encounter, $this->encounters);
        $record->difficulty = array_search($ranking->difficulty, $this->difficulties);
        $record->metric = $metric;
        $record->rank = $ranking->rank;
        $record->out_of = $ranking->outOf;
        $record->fight_duration = $ranking->duration;
        $record->percentile = $this->calculatePercentile($record->rank, $record->out_of);
        $record->report_id = $ranking->reportID;
        $record->fight_id = $ranking->fightID;
        $record->size = $ranking->size;
        $record->item_level = $ranking->itemLevel;
        $record->total = $ranking->total;
        $record->character_name = $characterName;
        return $record;
    }

    private function calculatePercentile($ranking, $outOf)
    {
        $percent = ($ranking / $outOf) * 100;

        return round(100 - $percent);
    }
}