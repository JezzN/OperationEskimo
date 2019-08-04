<?php
/**
 * Created by PhpStorm.
 * User: Jezz
 * Date: 30/12/2018
 * Time: 13:51
 */

namespace App\OE\Discord\Tasks;


use App\OE\Discord\Bot\Commands\HoAHallOfFame;
use App\OE\Discord\Bot\Commands\MythicPlus;
use App\OE\Discord\OperationEskimoDiscord;
use Carbon\Carbon;
use Discord\Discord;

class MythicPlusTask extends DiscordTask
{
    protected $interval = 60;
    /**
     * @var MythicPlus
     */
    private $mythicPlus;
    /**
     * @var HoAHallOfFame
     */
    private $hallOfFame;

    public function __construct(MythicPlus $mythicPlus, HoAHallOfFame $hallOfFame)
    {
        $this->mythicPlus = $mythicPlus;
        $this->hallOfFame = $hallOfFame;
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
        $now = Carbon::now()->setTimezone("Europe/Paris");

        if ($now->dayOfWeek == Carbon::TUESDAY && $now->hour == 9 && $now->minute == 17) {
            $operationEskimoDiscord->sendMessageToGeneralChat($this->mythicPlus->generateMessage());
        }

        if ($now->dayOfWeek == Carbon::MONDAY && $now->hour == 19 && $now->minute == 0) {
            $operationEskimoDiscord->sendMessageToBossDiscussion($this->hallOfFame->generateMessage());
        }

        if ($now->dayOfWeek == Carbon::WEDNESDAY && $now->hour == 19 && $now->minute == 0) {
            $operationEskimoDiscord->sendMessageToBossDiscussion($this->hallOfFame->generateMessage());
        }
    }
}