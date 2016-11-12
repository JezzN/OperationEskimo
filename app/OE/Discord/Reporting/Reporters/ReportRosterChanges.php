<?php
namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\WoW\GuildMemberChange;
use Discord\Discord;

class ReportRosterChanges extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $changes = $this->getNewRecords(GuildMemberChange::whereNotNull('character_name'));

        foreach( $changes as $change ) {
            $oeDiscord->sendMessageToOfficerChat($change->event);
        }
    }
}