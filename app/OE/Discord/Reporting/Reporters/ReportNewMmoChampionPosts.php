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

        $items = $this->getNewRecords(NewsItem::where('publish_date', '>', Carbon::now()->subHour(5)));

        foreach( $items as $item ) {
            $oeDiscord->sendMessageToGeneralChat("*$item->title* ($item->link)");
        }
    }
}