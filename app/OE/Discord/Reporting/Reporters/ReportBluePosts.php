<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 26/07/2018
 * Time: 08:01
 */

namespace App\OE\Discord\Reporting\Reporters;


use App\OE\Blues\BluePost;
use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use Carbon\Carbon;
use Discord\Discord;

class ReportBluePosts extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $items = $this->getNewRecords(BluePost::where('published_at', '>', Carbon::now()->subHour(24)));

        foreach( $items as $item ) {
            $link = $item->getLink();
            $oeDiscord->sendMessageToGeneralChat("*{$item->title}* ({$link})");
        }
    }
}