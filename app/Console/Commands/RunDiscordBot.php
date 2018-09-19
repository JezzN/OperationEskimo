<?php

namespace App\Console\Commands;

use App\OE\Discord\Bot\Commander;
use App\OE\Discord\Bot\Commands\MythicPlus;
use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Reporting\DiscordReporter;
use App\OE\Discord\Utility\StartWipeFestBot;
use App\OE\Forum\Discussion;
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

    /**
     * ReportNewForumThreadsToDiscord constructor.
     */
    public function __construct(Discord $discord, Commander $commander, DiscordReporter $reporter, StartWipeFestBot $startWipeFestBot, MythicPlus $mythicPlus)
    {
        parent::__construct();
        $this->discord = $discord;
        $this->commander = $commander;
        $this->reporter = $reporter;
        $this->startWipeFestBot = $startWipeFestBot;
        $this->mythicPlus = $mythicPlus;
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
        });

        $this->discord->on(Event::MESSAGE_CREATE, function($message) {
            $this->startWipeFestBot->start($message);
            $this->commander->execute($message, $this->discord);
        });

        $this->discord->run();
    }
}
