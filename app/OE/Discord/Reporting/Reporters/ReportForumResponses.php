<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Forum\Post;
use Discord\Discord;

class ReportForumResponses extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $query = Post::where('is_approved', true)->whereNull('hide_time')->where('type', 'comment')->whereHas('discussion.tags', function($q) {
           return $q->where('slug', 'raiding');
        });

        $discussions = $this->getNewRecords($query);

        foreach( $discussions as $discussion ) {
            $message = $this->createMessage($discussion);

            $oeDiscord->sendMessageToBossDiscussion($message);
        }
    }

    private function createMessage(Post $post)
    {
        $username = $post->user->username;
        $discussion = $post->discussion;

        $link = config('operation-eskimo.forum-link') . "d/{$discussion->id}-{$discussion->slug}";

        $shortMessage = strip_tags($post->content);

        if( strlen($shortMessage) > 100 ) {
            $shortMessage = substr($shortMessage, 0, 100) . '...';
        }

        return "{$username} responsed to **{$discussion->title}** (" . $link . "): {$shortMessage}";
    }
}