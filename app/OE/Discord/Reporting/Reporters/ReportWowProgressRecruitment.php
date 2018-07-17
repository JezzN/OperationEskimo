<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 17/07/2018
 * Time: 09:17
 */

namespace App\OE\Discord\Reporting\Reporters;


use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\Discord\Reporting\DiscordReporter;
use App\OE\WowProgress\Character;
use Discord\Discord;

class ReportWowProgressRecruitment extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $changes = $this->getNewRecords(Character::whereNotNull('character_name'));

        foreach( $changes as $change ) {
            $oeDiscord->sendMessageToOfficerChat("{$change->character_name} is looking for a guild on WoWProgress since {$change->last_updated}: {$change->url}");
        }
    }
}