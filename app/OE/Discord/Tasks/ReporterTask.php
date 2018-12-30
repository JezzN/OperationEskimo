<?php
/**
 * Created by PhpStorm.
 * User: Jezz
 * Date: 30/12/2018
 * Time: 13:52
 */

namespace App\OE\Discord\Tasks;


use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\DiscordReporter;
use Discord\Discord;

class ReporterTask extends DiscordTask
{
    protected $interval = 10;

    /**
     * @var DiscordReporter
     */
    private $reporter;

    public function __construct(DiscordReporter $reporter)
    {
        $this->reporter = $reporter;
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
        $this->reporter->report($discord);
    }
}