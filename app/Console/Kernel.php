<?php

namespace App\Console;

use App\Console\Commands\CheckWoWProgressRecruitment;
use App\Console\Commands\GuildLootImport;
use App\Console\Commands\GuildItemLevelUpdate;
use App\Console\Commands\GuildMemberCheck;
use App\Console\Commands\ImportBluePosts;
use App\Console\Commands\ImportCharacterRep;
use App\Console\Commands\ImportGuildMembers;
use App\Console\Commands\ImportMmoChampionThreads;
use App\Console\Commands\ImportRecruitmentFromWowProgress;
use App\Console\Commands\ImportWarcraftLogsRankings;
use App\Console\Commands\ParseForumConfigurationThreads;
use App\Console\Commands\ReadWarcraftLogs;
use App\Console\Commands\RunDiscordBot;
use App\Console\Commands\ReportNewThreads;
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
        GuildLootImport::class,
        ParseForumConfigurationThreads::class,
        RunDiscordBot::class,
        ImportGuildMembers::class,
        ImportMmoChampionThreads::class,
        CheckWoWProgressRecruitment::class,
        ImportBluePosts::class,
        ImportRecruitmentFromWowProgress::class,
        ImportCharacterRep::class
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
