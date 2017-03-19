<?php
namespace App\OE\Discord\Reporting;

use App\OE\Discord\Reporting\Reporters\ReportForumResponses;
use App\OE\Discord\Reporting\Reporters\ReportLegendaryDrops;
use App\OE\Discord\Reporting\Reporters\ReportNewForumThreads;
use App\OE\Discord\Reporting\Reporters\ReportNewMmoChampionPosts;
use App\OE\Discord\Reporting\Reporters\ReportRosterChanges;
use App\OE\Discord\Reporting\Reporters\ReportWarcraftLogRankings;
use Discord\Discord;

class DiscordReporter
{
    private $reports = [
        ReportNewForumThreads::class,
        ReportLegendaryDrops::class,
        ReportRosterChanges::class,
        ReportNewMmoChampionPosts::class,
        ReportForumResponses::class
    ];

    public function report(Discord $discord)
    {
        foreach( $this->reports as $report ) {
            app($report)->execute($discord);
        }
    }
}