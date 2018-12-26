<?php

namespace App\Console\Commands;

use App\OE\Discord\Bot\Commander;
use App\OE\Discord\Bot\Commands\HoAHallOfFame;
use App\OE\Discord\Bot\Commands\MythicPlus;
use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\DiscordReporter;
use App\OE\Discord\Utility\StartWipeFestBot;
use App\OE\Forum\Discussion;
use App\OE\WoW\HeartOfAzeroth;
use App\WoW\Incursion;
use Carbon\Carbon;
use Discord\Discord;
use Discord\WebSockets\Event;
use Illuminate\Cache\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class RunDiscordBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord-bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the discord bot.';

    /** @var  Discord */
    private $discord;

    /** @var Commander */
    private $commander;

    /** @var DiscordReporter */
    private $reporter;
    /**
     * @var StartWipeFestBot
     */
    private $startWipeFestBot;
    /**
     * @var MythicPlus
     */
    private $mythicPlus;
    /** @var HoAHallOfFame */
    private $hallOfFame;

    /**
     * ReportNewForumThreadsToDiscord constructor.
     */
    public function __construct(Discord $discord, Commander $commander, DiscordReporter $reporter, StartWipeFestBot $startWipeFestBot, MythicPlus $mythicPlus, HoAHallOfFame $hallOfFame)
    {
        parent::__construct();
        $this->discord = $discord;
        $this->commander = $commander;
        $this->reporter = $reporter;
        $this->startWipeFestBot = $startWipeFestBot;
        $this->mythicPlus = $mythicPlus;
        $this->hallOfFame = $hallOfFame;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->discord->loop->addPeriodicTimer(10, function() {
            $this->reporter->report($this->discord);
        });

        $this->discord->loop->addPeriodicTimer(60, function() {
            $now = Carbon::now()->setTimezone("Europe/Paris");

            if ($now->dayOfWeek == Carbon::TUESDAY && $now->hour == 9 && $now->minute == 17) {
                OperationEskimoDiscord::forServer($this->discord)->sendMessageToGeneralChat($this->mythicPlus->generateMessage());
            }

            if ($now->dayOfWeek == Carbon::WEDNESDAY && $now->hour == 19 && $now->minute == 0) {
                OperationEskimoDiscord::forServer($this->discord)->sendMessageToBossDiscussion($this->hallOfFame->generateMessage());
            }

            if ($now->dayOfWeek == Carbon::SUNDAY && $now->hour == 19 && $now->minute == 0) {
                OperationEskimoDiscord::forServer($this->discord)->sendMessageToBossDiscussion($this->hallOfFame->generateMessage());
            }
        });

        $this->discord->loop->addPeriodicTimer(1, function() {
            $incursions = new Incursion();
            $incursion = $incursions->getActiveIncursion();

            if (!$incursion) return;

            if(Carbon::now()->second(0)->eq($incursion['start_time'])) {
                OperationEskimoDiscord::forServer($this->discord)->sendMessageToIncursionsChannel("An incursion has started in ". $incursion['zone'] . " and will end at " . $incursion['end_time']->setTimezone('Europe/Paris')->format('ga'));
            }
        });

        $this->discord->on(Event::MESSAGE_CREATE, function($message) {
            $this->startWipeFestBot->start($message);
            $this->commander->execute($message, $this->discord);
        });

        $this->discord->run();
    }
}
