<?php
namespace App\OE\Discord\Reporting;

use App\OE\Discord\Reporting\Reporters\ReportLegendaryDrops;
use App\OE\Discord\Reporting\Reporters\ReportNewForumThreads;
use Discord\Discord;

class DiscordReporter
{
    private $reports = [
        ReportNewForumThreads::class,
        ReportLegendaryDrops::class
    ];

    public function report(Discord $discord)
    {
        foreach( $this->reports as $report ) {
            app($report)->execute($discord);
        }
    }
}