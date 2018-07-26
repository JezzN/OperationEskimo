<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 26/07/2018
 * Time: 07:38
 */

namespace App\Console\Commands;


use App\OE\Blues\BluePost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportBluePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blue:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import blue posts via rss feed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "http://www.wowhead.com/bluetracker&rss";

        $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);

        foreach( $xml->channel->item as $item ) {
            if (!$this->isStartedByBlizzard($item)) continue;

            $topicId = $this->extractTopicId($item);

            if (BluePost::where('topic_id', $topicId)->exists()) continue;

            $bluePost = new BluePost();
            $bluePost->topic_id = $topicId;
            $bluePost->title = $item->title;
            $bluePost->description = $item->description;
            $bluePost->published_at = new Carbon(explode(' -', $item->pubDate)[0]);
            $bluePost->save();
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function isStartedByBlizzard($item)
    {
        return str_contains($item->description, 'poster=Blizzard');
    }

    private function extractTopicId($item)
    {
        return explode('topic=', $item->link)[1];
    }
}