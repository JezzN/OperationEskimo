<?php

namespace App\Console\Commands;

use App\OE\OperationEskimo;
use App\OE\WoW\CharacterRep;
use App\OE\WoW\HeartOfAzeroth;
use Illuminate\Console\Command;
use Pwnraid\Bnet\Warcraft\Characters\CharacterRequest;
use Pwnraid\Bnet\Warcraft\Client;

class ImportCharacterHeartOfAzeroth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:import-heart-of-azeroth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import heart of azeroth data.';

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
            $items = $this->client->characters()->on('Ragnaros')->find($character->character_name, ['items']);

            if (!$items) continue;

            $heart = $items->items['neck'];

            if($heart['name'] !== 'Heart of Azeroth') continue;

            $heartDb = HeartOfAzeroth::where('character_name', $items->name)->first();

            if (!$heartDb) {
                $heartDb = new HeartOfAzeroth();
                $heartDb->character_name = $items->name;
            }

            $heartDb->level = $heart['azeriteItem']['azeriteLevel'];
            $heartDb->experience = $heart['azeriteItem']['azeriteExperience'];
            $heartDb->experience_remaining = $heart['azeriteItem']['azeriteExperienceRemaining'];
            $heartDb->save();
        }

        foreach (HeartOfAzeroth::all() as $heart) {
            if(!$this->operationEskimo->raiders()->pluck('character_name')->contains($heart->character_name)) {
                $heart->delete();
            }
        }
    }
}