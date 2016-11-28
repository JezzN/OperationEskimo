<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\MmoChampion\NewsItem;
use Carbon\Carbon;
use Discord\Discord;

class ReportNewMmoChampionPosts extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $items = $this->getNewRecords(NewsItem::where('publish_date', '>', Carbon::now()->subDay(5)));

        foreach( $items as $item ) {
            $oeDiscord->sendMessageToTestChat("[$item->title]($item->link)");
        }
    }
}