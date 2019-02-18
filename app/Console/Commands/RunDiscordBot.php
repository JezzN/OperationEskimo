<?php

namespace App\Console\Commands;

use App\OE\Discord\Bot\Commander;
use App\OE\Discord\Bot\Commands\Dangerzone;
use App\OE\Discord\Bot\Commands\HoAHallOfFame;
use App\OE\Discord\Bot\Commands\IncursionCommand;
use App\OE\Discord\Bot\Commands\ListCommands;
use App\OE\Discord\Bot\Commands\MythicPlus;
use App\OE\Discord\Bot\Commands\Trials;
use App\OE\Discord\OperationEskimoDiscord;
use App\OE\Discord\Tasks\IncursionTask;
use App\OE\Discord\Tasks\MythicPlusTask;
use App\OE\Discord\Tasks\ReporterTask;
use Discord\Discord;
use Discord\WebSockets\Event;
use Illuminate\Console\Command;

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

    private $tasks = [
        ReporterTask::class,
        MythicPlusTask::class,
//        IncursionTask::class
    ];

    private $commands = [
        'commands' => ListCommands::class,
        'dangerzone' => Dangerzone::class,
        'trials' => Trials::class,
        'mythicplus' => MythicPlus::class,
        'hof' => HoAHallOfFame::class,
        'incursion' => IncursionCommand::class
    ];

    public function __construct(Discord $discord, Commander $commander)
    {
        parent::__construct();
        $this->discord = $discord;
        $this->commander = $commander;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->registerDiscordTasks();

        $this->registerCommandListener();

        $this->discord->run();
    }

    /**
     * Register the recurring discord tasks.
     *
     */
    public function registerDiscordTasks()
    {
        foreach ($this->tasks as $task) {
            $task = app($task);

            $this->discord->loop->addPeriodicTimer($task->getInterval(), function () use ($task) {
                $task->execute($this->discord, OperationEskimoDiscord::forServer($this->discord));
            });
        }
    }

    /**
     * Listens for and parses any typed commands.
     *
     */
    public function registerCommandListener()
    {
        $this->commander->register($this->commands);

        $this->discord->on(Event::MESSAGE_CREATE, function ($message) {
            $this->commander->execute($message);
        });
    }
}
