<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 23/10/2016
 * Time: 15:01
 */

namespace App\Console\Commands;


use App\Mail\GuildMemberLeft;
use App\OE\Guild;
use App\OE\Loot\GuildLootDropsImporter;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class GuildLootImport extends Command
{
    protected $signature = 'guild:import-loot';

    protected $description = 'Imports any loot received by guild members to the database';

    /** @var GuildLootDropsImporter */
    private $lootFeed;

    /** @var Repository */
    private $cache;

    public function __construct(GuildLootDropsImporter $lootFeed, Repository $cache)
    {
        $this->lootFeed = $lootFeed;
        $this->cache    = $cache;
        parent::__construct();
    }

    public function handle()
    {
        $this->lootFeed->import();
    }
}