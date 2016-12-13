<?php
namespace App\OE\WoW;

use Pwnraid\Bnet\Warcraft\Client;

class ArtifactImporter
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Import artifact level and information for the guild member.
     *
     * @author Jeremy
     * @param GuildMember $member
     */
    public function importArtifactFor(GuildMember $member)
    {
        $character = $this->client->characters()->on('Ragnaros')->find($member->character_name, ['items']);

        $weapon = $character['items']['mainHand'];

        if( empty($weapon['artifactTraits'])  ) return;

        $rank = $this->calculateRank($weapon);

        $this->insertArtifact($member, $weapon['name'], $rank, $weapon['itemLevel']);
    }

    private function insertArtifact(GuildMember $guildMember, $name, $rank, $level)
    {
        $record = Artifact::where('member_id', $guildMember->id)->where('artifact_name', $name)->first();

        if( ! $record ) {
            $record = new Artifact();
            $record->member_id = $guildMember->id;
            $record->artifact_name = $name;
        }

        $record->rank = $rank;
        $record->level = $level;
        $record->save();
    }

    private function calculateRank($weapon)
    {
        $relicCount = count($weapon['relics']);

        $rank = 0;

        foreach( $weapon['artifactTraits'] as $trait ) {
            $rank += $trait['rank'];
        }

        $adjustedRank = $rank - $relicCount;

        return $adjustedRank > 0 ? $adjustedRank : 0;
    }
}