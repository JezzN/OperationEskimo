<?php
/**
 * Created by PhpStorm.
 * User: Jezz
 * Date: 30/12/2018
 * Time: 13:52
 */

namespace App\OE\Discord\Tasks;


use App\OE\Discord\OperationEskimoDiscord;
use App\WoW\Incursion;
use Carbon\Carbon;
use Discord\Discord;

class IncursionTask extends DiscordTask
{
    protected $interval = 45;
    /**
     * @var Incursion
     */
    private $incursions;

    /**
     * IncursionTask constructor.
     * @param Incursion $incursions
     */
    public function __construct(Incursion $incursions)
    {
        $this->incursions = $incursions;
    }

    /**
     * Execute the recurring task.
     *
     * @param Discord $discord
     * @param OperationEskimoDiscord $operationEskimoDiscord
     * @return mixed
     */
    public function execute(Discord $discord, OperationEskimoDiscord $operationEskimoDiscord)
    {
        $incursion = $this->incursions->getActiveIncursion();

        if (!$incursion) return;

        if(Carbon::now()->second(0)->eq($incursion['start_time'])) {
            $operationEskimoDiscord->sendMessageToIncursionsChannel("An incursion has started in ". $incursion['zone'] . " and will end at " . $incursion['end_time']->setTimezone('Europe/Paris')->format('ga'));
        }

        if(Carbon::now()->second(0)->addHour()->eq($incursion['end_time']->setTimezone('Europe/Paris'))) {
            $nextStartTime = $this->incursions->getNextIncursion(Carbon::now()->addHour())['start_time']->setTimezone('Europe/Paris');

           $operationEskimoDiscord->sendMessageToIncursionsChannel("The incursion in ". $incursion['zone'] . " will end in 1 hour, the next incursion starts at " . $nextStartTime->format('ga'));
        }
    }
}