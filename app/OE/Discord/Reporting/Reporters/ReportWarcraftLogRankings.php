<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\WarcraftLogs\Ranking;
use Discord\Discord;

class ReportWarcraftLogRankings extends AbstractDatabaseChangeReporter
{
    protected $maxRecords = 25;

    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $query = Ranking::where('percentile', '>=', 95);

        $changes = $this->getNewRecords($query);

        foreach( $changes->chunk(5) as $chunk ) {
            $oeDiscord->sendMessageToGeneralChat($this->createMessage($chunk));
        }
    }

    private function createMessage($chunk)
    {
        $message = '';

        foreach( $chunk as $ranking ) {
            $difficulty = ucfirst($ranking->difficulty);
            $metric = strtoupper($ranking->metric);
            $percentile = $ranking->percentile >= 99 ? 99 : $ranking->percentile;

            $message .= "{$ranking->character_name} ranked {$percentile}% on {$difficulty} {$ranking->encounter} with {$ranking->total} {$metric}" . PHP_EOL . PHP_EOL;
        }

        return $message;
    }
}