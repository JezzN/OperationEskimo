<?php
namespace App\OE\Discord\Reporting;

use App\OE\Discord\Reporting\Reporters\ReportBluePosts;
use App\OE\Discord\Reporting\Reporters\ReportForumResponses;
use App\OE\Discord\Reporting\Reporters\ReportLegendaryDrops;
use App\OE\Discord\Reporting\Reporters\ReportMythicChestItems;
use App\OE\Discord\Reporting\Reporters\ReportNewForumThreads;
use App\OE\Discord\Reporting\Reporters\ReportNewMmoChampionPosts;
use App\OE\Discord\Reporting\Reporters\ReportRealmStatus;
use App\OE\Discord\Reporting\Reporters\ReportRosterChanges;
use App\OE\Discord\Reporting\Reporters\ReportWarcraftLogRankings;
use App\OE\Discord\Reporting\Reporters\ReportWowProgressRecruitment;
use Discord\Discord;

class DiscordReporter
{
    private $reports = [
        ReportNewForumThreads::class,
        ReportRosterChanges::class,
        ReportNewMmoChampionPosts::class,
        ReportForumResponses::class,
        ReportMythicChestItems::class,
        ReportWowProgressRecruitment::class,
        ReportBluePosts::class,
        ReportRealmStatus::class
    ];

    public function report(Discord $discord)
    {
        foreach( $this->reports as $report ) {
            app($report)->execute($discord);
        }
    }
}