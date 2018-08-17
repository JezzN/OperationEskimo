<?php

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WoW\CharacterRep;
use Illuminate\Console\Command;
use Pwnraid\Bnet\Warcraft\Characters\CharacterRequest;
use Pwnraid\Bnet\Warcraft\Client;

class ImportCharacterRep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:import-reps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import guild member reps.';
    /** @var OperationEskimo */
    private $operationEskimo;
    /** @var Client */
    private $client;

    /**
     * ImportCharacterRep constructor.
     */
    public function __construct(OperationEskimo $operationEskimo, Client $client)
    {
        parent::__construct();
        $this->operationEskimo = $operationEskimo;
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->operationEskimo->raiders() as $character) {
            $character = $this->client->characters()->on('Ragnaros')->find($character->character_name, ['reputation']);

            foreach ($character->reputation as $reputation) {
                $characterReputation = CharacterRep::where('rep_id', $reputation['id'])->where('character_name', $character->name)->first();

                if (!$characterReputation) {
                    $characterReputation = new CharacterRep();
                    $characterReputation->character_name = $character->name;
                    $characterReputation->rep_id = $reputation['id'];
                    $characterReputation->reputation_name = $reputation['name'];
                }

                $characterReputation->standing = $reputation['standing'];
                $characterReputation->value = $reputation['value'];
                $characterReputation->max = $reputation['max'];
                $characterReputation->save();
            }
        }
    }
}