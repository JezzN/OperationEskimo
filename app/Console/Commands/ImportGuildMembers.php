<?php

namespace App\Console\Commands;

use App\OE\Loot\ItemLevelFetcher;
use App\OE\WoW\OeGuildApiResponse;
use App\OE\WoW\GuildMember;
use App\OE\WoW\GuildMemberChange;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Pwnraid\Bnet\Warcraft\Guilds\GuildEntity;

class ImportGuildMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import guild members into database';

    /** @var OeGuildApiResponse */
    private $oe;
    /** @var ItemLevelFetcher */
    private $itemLevelFetcher;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OeGuildApiResponse $oe, ItemLevelFetcher $itemLevelFetcher)
    {
        parent::__construct();
        $this->oe = $oe;
        $this->itemLevelFetcher = $itemLevelFetcher;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $guildMembers = new Collection();

        foreach(  $this->oe->allMembers() as $member )
        {
            $guildMembers[] = $this->createGuildMember($member, $this->itemLevelFetcher->averageForCharacter($member));
        }

        $this->removeNonGuildMembers($guildMembers);
    }

    private function createGuildMember($characterInfo, $itemLevel)
    {
        $rank = $characterInfo['rank'];
        $character = $characterInfo['character']['character'];

        $member = GuildMember::where('character_name_hash_lookup', sha1($character['name']))->first();

        if( ! $member ) {
            $member = new GuildMember();
            $member->character_name = $character['name'];
            $member->character_name_hash_lookup = sha1($character['name']);
        }

        $member->rank = $rank + 1;
        $member->average_item_level = $itemLevel['average'];
        $member->average_item_level_equipped = $itemLevel['equipped'];
        $member->class = $character['class'];
        $member->spec = isset($character['spec']) ? strtolower($character['spec']['name']) : null;
        $member->archetype = strtolower($this->oe->getRole($characterInfo));
        $member->role = strtolower($member->archetype === 'dps' ? $this->oe->determineDpsType($characterInfo) : $member->archetype);

        if($member->isDirty()) {
            $this->alertOnChanges($member, $member->getDirty());
        }

        $member->save();

        return $member;
    }

    protected function alertOnChanges(GuildMember $member, $changes)
    {
        if( ! in_array('rank', array_keys($changes)) ) return;

        $existing = GuildMember::where('character_name_hash_lookup', $member->character_name_hash_lookup)->first();

        if( ! $existing ) return;

        $currentRank = $existing->rankName();
        $newRank = $member->rankName();

        GuildMemberChange::fromEventString($member->character_name, "{$member->character_name}'s rank has been changed from {$currentRank} to {$newRank}");
    }

    private function removeNonGuildMembers($guildMembers)
    {
        $exGuildMembers = GuildMember::whereNotIn('character_name', $guildMembers->pluck('character_name'))->get();

        foreach( $exGuildMembers as $exGuildMember ) {
            GuildMemberChange::fromEventString($exGuildMember->character_name, "{$exGuildMember->character_name} ({$exGuildMember->rankName()}) is no longer in the guild");
        }

        GuildMember::whereNotIn('character_name', $guildMembers->pluck('character_name'))->delete();
    }
}
