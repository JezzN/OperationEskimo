<?php
namespace App\OE;

use App\OE\Configuration\RosterConfiguration;
use App\OE\WoW\GuildMember;

class OperationEskimo
{
    /** @var RosterConfiguration */
    private $rosterConfiguration;

    public function __construct(RosterConfiguration $rosterConfiguration)
    {
        $this->rosterConfiguration = $rosterConfiguration;
    }

    public function tanks()
    {
        return $this->getByArchetype('tank');
    }

    public function healers()
    {
        return $this->getByArchetype('healing');
    }

    public function raiders()
    {
        return $this->raidersQuery()->get();
    }

    private function raidersQuery()
    {
        return GuildMember::whereIn('rank', [1,2,5,6,7])->whereNotIn('character_name', $this->rosterConfiguration->getExcluded());
    }

    public function unknown()
    {
        return $this->getByArchetype('unknown');
    }

    private function getByArchetype($archetype)
    {
        return $this->raidersQuery()->where('archetype', $archetype)->get();
    }

    public function ranged()
    {
        return $this->raidersQuery()->where('role', 'ranged')->get();
    }

    public function melee()
    {
        return $this->raidersQuery()->where('role', 'melee')->get();
    }

    public function dps()
    {
        return $this->raidersQuery()->where('archetype', 'dps')->get();
    }

    public function roleCounts()
    {
        return [
            'officers' => count($this->byRank([1,2])),
            'members' => count($this->byRank([5,6])),
            'trials' => count($this->byRank([7]))
        ];
    }

    private function byRank($rank)
    {
        return $this->raidersQuery()->whereIn('rank', $rank)->get();
    }
}