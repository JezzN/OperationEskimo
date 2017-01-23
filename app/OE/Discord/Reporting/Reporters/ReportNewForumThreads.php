<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Forum\Discussion;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Illuminate\Cache\Repository;

class ReportNewForumThreads extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
   {
       $oeDiscord = OperationEskimoDiscord::forServer($discord);

       $discussions = $this->getNewRecords(Discussion::where('is_approved', true)->whereNull('hide_time'));

       foreach( $discussions as $discussion ) {
           $message = $this->createMessage($discussion);

           $oeDiscord->sendMessageToOfficerChat($message);

           if( ! $this->isOfficerThread($discussion) ) {
               $oeDiscord->sendMessageToGeneralChat($message);
           }
       }
   }

   private function isOfficerThread(Discussion $discussion)
   {
        return in_array('officer-chat', $discussion->tags->pluck('slug')->toArray());
   }

    private function createMessage($discussion)
    {
        $username = $discussion->user->username;

        $link = config('operation-eskimo.forum-link') . "d/{$discussion->id}-{$discussion->slug}";

        return "{$username} created new forum thread **{$discussion->title}** (" . $link . ")";
    }
}