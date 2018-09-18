<?php

namespace App\OE\Discord\Reporting\Reporters;

use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\AbstractDatabaseChangeReporter;
use App\OE\WoW\RealmStatus;
use Discord\Discord;

class ReportRealmStatus extends AbstractDatabaseChangeReporter
{
    public function execute(Discord $discord)
    {
        $oeDiscord = OperationEskimoDiscord::forServer($discord);

        $latestRealmStatuses = $this->getNewRecords(RealmStatus::where('realm_name', 'Ragnaros'));

        foreach( $latestRealmStatuses as $realmStatus ) {
            $status = $realmStatus->is_up ? "is back up" : "has gone down";

            $oeDiscord->sendMessageToGeneralChat("{$realmStatus->realm_name} {$status}");
        }
    }
}