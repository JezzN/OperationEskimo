<?php

namespace App\Console\Commands;

use App\OE\MmoChampion\NewsItem;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportMmoChampionThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmo-champion:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new news posts from mmo champion.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "http://www.mmo-champion.com/external.php?do=rss&type=newcontent&sectionid=1&days=120&count=5";

        $xml = simplexml_load_file($url);

        foreach( $xml->channel->item as $item ) {
            $this->insertNewsItem($item);
        }
    }

    private function insertNewsItem($item)
    {
        $hasImported = (bool) NewsItem::where('guid', $item->guid)->count();

        if( $hasImported ) return;

        $mmoChampNewsItem = new NewsItem();
        $mmoChampNewsItem->publish_date = new Carbon($item->pubDate);
        $mmoChampNewsItem->title = $item->title;
        $mmoChampNewsItem->link = $item->link;
        $mmoChampNewsItem->guid = $item->guid;
        $mmoChampNewsItem->save();
    }
}
