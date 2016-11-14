<?php

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WarcraftLogs\Ranking;
use App\OE\WarcraftLogs\WarcraftLogs;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ImportWarcraftLogsRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import rankings from warcraft logs';

    /** @var OperationEskimo */
    private $oe;

    /** @var WarcraftLogs */
    private $logs;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WarcraftLogs $logs, OperationEskimo $oe)
    {
        parent::__construct();
        $this->oe = $oe;
        $this->logs = $logs;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if( Carbon::now()->hour < 19 ) return;

        foreach( $this->oe->raiders() as $raider ) {

            $rankings = $this->logs->getRankings($raider->character_name);

            foreach( $rankings as $ranking ) {
                if( $this->hasAlreadyBeenInserted($ranking) ) continue;

                $ranking->save();
            }
        }
    }

    private function hasAlreadyBeenInserted(Ranking $ranking)
    {
        return (bool) Ranking::where('report_id', $ranking->report_id)
            ->where('fight_id', $ranking->fight_id)
            ->where('character_name', $ranking->character_name)
            ->where('difficulty', $ranking->difficulty)
            ->count();
    }

}
