<?php
namespace App\OE\News;

use App\OE\Forum\Post;
use Illuminate\Database\DatabaseManager;

class NewsRepository
{
    const NEWS_TAG = 'news';
    /** @var DatabaseManager */
    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }


    public function latest()
    {
        $tagId = $this->db->connection('forums')->table('tags')->where('slug', static::NEWS_TAG)->first()->id;

        $newsDiscussions = $this->db->connection('forums')->table('discussions_tags')->where('tag_id', $tagId)->pluck('discussion_id');

        return Post::whereIn('discussion_id', $newsDiscussions)->orderBy('time', 'desc')->paginate(5);
    }
}