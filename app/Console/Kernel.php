<?php

namespace App\Console;

use App\Console\Commands\GuildLootImport;
use App\Console\Commands\GuildItemLevelUpdate;
use App\Console\Commands\GuildMemberCheck;
use App\Console\Commands\ParseForumConfigurationThreads;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GuildMemberCheck::class,
        GuildLootImport::class,
        GuildItemLevelUpdate::class,
        ParseForumConfigurationThreads::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
