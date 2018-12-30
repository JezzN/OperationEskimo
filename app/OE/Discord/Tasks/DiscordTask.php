<?php

namespace App\OE\Discord\Tasks;

use App\OE\Discord\OperationEskimoDiscord;
use Discord\Discord;

/**
 * A way to define a task that will regularly occur when the discord bot is running.
 *
 * Class DiscordTask
 * @package App\OE\Discord\Tasks
 */
abstract class DiscordTask
{
    protected $interval = 60;

    /**
     * The seconds
     *
     * @return mixed
     */
    public function getInterval() : int {
        return $this->interval;
    }

    /**
     * Execute the recurring task.
     *
     * @param Discord $discord
     * @param OperationEskimoDiscord $operationEskimoDiscord
     * @return mixed
     */
    public abstract function execute(Discord $discord, OperationEskimoDiscord $operationEskimoDiscord);
}