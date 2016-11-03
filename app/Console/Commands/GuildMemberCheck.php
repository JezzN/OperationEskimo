<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 23/10/2016
 * Time: 15:01
 */

namespace App\Console\Commands;


use App\Mail\GuildMemberLeft;
use App\OE\Forum\User;
use App\OE\Guild;
use App\OE\OperationEskimo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class GuildMemberCheck extends Command
{
    protected $signature = 'guild:member-check';

    protected $description = 'Monitors the guild roster and alerts the officers if any members were to leave';

    /** @var OperationEskimo */
    private $operationEskimo;

    public function __construct(OperationEskimo $operationEskimo)
    {
        $this->operationEskimo = $operationEskimo;
        parent::__construct();
    }

    public function handle()
    {
        $raiders = $this->operationEskimo->raiders()->pluck('character.character.name')->toArray();

        $lastSetOfRaiders = Cache::get('operation_eskimo_raiders', []);

        Cache::forever('operation_eskimo_raiders', $raiders);

        if( count($lastSetOfRaiders) == 0 ) {
            return;
        }

        $membersLeft = array_diff($lastSetOfRaiders, $raiders);

        if( count($membersLeft) == 0 ) return;

        Mail::to($this->getAlertEmailAddresses())->send(new GuildMemberLeft($membersLeft)); //@todo stop hardcoding
    }

    private function getAlertEmailAddresses()
    {
        return User::whereHas('groups', function($q) {
            $q->where(function($q) {
                $q->where('name_singular', 'Officer')->orWhere('name_singular', 'Admin');
            });
        })->where('username', '!=', 'Maitoz')->pluck('email')->toArray();
    }
}