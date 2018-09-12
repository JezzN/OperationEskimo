<?php

namespace App\Console\Commands;

use App\OE\Discord\Bot\Commander;
use App\OE\Discord\Reporting\DiscordReporter;
use App\OE\Discord\Utility\StartWipeFestBot;
use App\OE\Forum\Discussion;
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
     * ReportNewForumThreadsToDiscord constructor.
     */
    public function __construct(Discord $discord, Commander $commander, DiscordReporter $reporter, StartWipeFestBot $startWipeFestBot)
    {
        parent::__construct();
        $this->discord = $discord;
        $this->commander = $commander;
        $this->reporter = $reporter;
        $this->startWipeFestBot = $startWipeFestBot;
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

        $this->discord->on(Event::MESSAGE_CREATE, function($message) {
            $this->startWipeFestBot->start($message);
            $this->commander->execute($message, $this->discord);
        });

        $this->discord->run();
    }
}
