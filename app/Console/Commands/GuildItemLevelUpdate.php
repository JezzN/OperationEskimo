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
use App\OE\Loot\ItemLevelFetcher;
use App\OE\OperationEskimo;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class GuildItemLevelUpdate extends Command
{
    protected $signature = 'guild:update-item-level';

    protected $description = 'Regularly caches the average item level of each raidr';

    /** @var OperationEskimo */
    private $operationEskimo;

    /** @var ItemLevelFetcher */
    private $itemLevelFetcher;

    public function __construct(OperationEskimo $operationEskimo, ItemLevelFetcher $itemLevelFetcher)
    {
        $this->operationEskimo = $operationEskimo;
        $this->itemLevelFetcher = $itemLevelFetcher;
        parent::__construct();
    }


    public function handle()
    {
        foreach( $this->operationEskimo->raiders() as $character ) {
            $this->itemLevelFetcher->averageForCharacter($character);
        }
    }
}