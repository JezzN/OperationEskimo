<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportRecruitmentFromWowProgress extends Command
{
    protected $signature = 'wowprogress:import-oe-recruitment';

    protected $description = 'Import wowprogress OE recruitment for the website';

    const OE_GUILD_LINK = 'https://www.wowprogress.com/guild/eu/ragnaros/Operation+Eskimo';

    public function handle()
    {
        $wowprogress = file_get_contents(self::OE_GUILD_LINK);

        dd($wowprogress);
    }
}