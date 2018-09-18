<?php

namespace App\Console\Commands;

use App\OE\WoW\RealmStatus;
use Illuminate\Console\Command;
use Pwnraid\Bnet\Warcraft\Client;
use Pwnraid\Bnet\Warcraft\Realms\RealmEntity;

class ImportRealmStatus extends Command
{
    protected $signature = 'realm-status:import';

    protected $description = 'Import realm status from the API';
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle()
    {
        $realm = 'Ragnaros';

        $realmEntity = $this->client->realms()->find($realm);

        $realmStatus = RealmStatus::where('realm_name', $realm)->orderBy('created_at', 'desc')->first();

        if (!$realmStatus) {
            $this->addRealmStatus($realmEntity);
            return;
        }

        if ($realmStatus->is_up != $realmEntity->status) {
            $this->addRealmStatus($realmEntity);
        }
    }

    private function addRealmStatus(RealmEntity $realmEntity) {
        $realmStatus = new RealmStatus();
        $realmStatus->realm_name = $realmEntity->name;
        $realmStatus->is_up = (bool) $realmEntity->status;
        $realmStatus->has_queue = (bool) $realmEntity->queue;
        $realmStatus->save();
    }
}