<?php
namespace App\OE;

use App\OE\Configuration\ConfigurationLoader;
use App\OE\Configuration\RosterConfiguration;
use Illuminate\Support\Collection;
use Pwnraid\Bnet\Warcraft\Characters\ClassEntity;
use Pwnraid\Bnet\Warcraft\Guilds\GuildEntity;

class OperationEskimo
{
    /** @var GuildEntity */
    private $guild;

    private $rosterConfiguration;

    public function __construct(GuildEntity $guild, RosterConfiguration $rosterConfiguration)
    {
        $this->guild = $guild;
        $this->rosterConfiguration = $rosterConfiguration;
    }

    /**
     * Returns all raiders, this includes all ranks from trial to gm.
     *
     * @return mixed
     * @author Jeremy
     */
    public function raiders() : Collection
    {
        return $this->filterByRank([0,1,4,5]);
    }

    public function isRaider($character)
    {
        return in_array($character, $this->raiders()->toArray());
    }

    /**
     * Filter the list of guild members by a specific rank.
     *
     * @return mixed
     * @param      $ranks
     * @param null $members
     * @author Jeremy
     */
    public function filterByRank($ranks, $members = null)
    {
        $members = $members ? $members : $this->getAllMembers();

        if( ! is_array($ranks) ) $ranks = [$ranks];

        return $members->filter(function($member) use ($ranks) {
            return in_array($member['rank'], $ranks);
        });
    }

    public function filterByRole($filterRole, $members = null)
    {
        if( ! $members ) $members = $this->getAllMembers();

        return $members->filter(function($member) use ($filterRole) {

            $character = $member['character']['character'];
            $characterName = $character['name'];

            $role = $this->getSpecifiedRole($characterName);

            if( ! $role ) {
                $role = isset($character['spec']) ? $character['spec']['role'] : null;
            }

            if( ! $role ) {
                $role = $this->isClassWithOnlyDpsSpec($character) ? 'dps' : null;
            }

            if( ! $role ) {
                $role = 'unknown';
            }

            return strtolower($filterRole) === strtolower($role);
        });
    }

    public function ranged()
    {
        $dps = $this->filterByRole('DPS', $this->raiders());

        return $dps->filter(function($member) {
            return $this->determineDpsType($member) === 'ranged';
        });
    }

    public function melee()
    {
        $dps = $this->filterByRole('DPS', $this->raiders());

        return $dps->filter(function($member) {
            return $this->determineDpsType($member) === 'melee';
        });
    }

    /**
     * Attempts to work out whether the DPS is a ranged or melee.
     *
     * @return string
     * @param $character
     * @internal param $member
     * @author   Jeremy
     */
    private function determineDpsType($character)
    {
        $character = $character['character']['character'];

        $name = $character['name'];
        $class = $character['class'];

        if( $role = $this->rosterConfiguration->getRole($name) ) return $role;

        if( in_array($class, [1,2,4,6,10,12]) ) return 'melee';

        if( $class === 7 ) {
            return $character['spec']['name'] === "Elemental" ? 'ranged' : 'melee';
        }

        if( $class === 11 ) {
            return $character['spec']['name'] === "Balance" ? 'ranged' : 'melee';
        }

        return 'ranged';
    }

    private function isClassWithOnlyDpsSpec($character)
    {
        return in_array($character['class'], [3,4,8,9]);
    }

    /**
     * Looks up the configuration to see if this character has an already specified role.
     *
     * @author Jeremy
     * @return string|void
     * @param $name
     */
    private function getSpecifiedRole($name)
    {
        $archetype = $this->rosterConfiguration->getRole($name);

        return in_array($archetype, ['melee', 'ranged']) ? 'dps' : $archetype;
    }

    /**
     * Returns all the members as character objects.
     *
     * @return static
     * @author Jeremy
     */
    private function getAllMembers()
    {
        return collect($this->guild->members)->filter(function($member) {
            return ! $this->rosterConfiguration->isExcluded($member['character']['character']['name']);
        });
    }

    public function roleCounts()
    {
        return [
            'officers' => count($this->filterByRank([0,1])),
            'members' => count($this->filterByRank(4)),
            'trials' => count($this->filterByRank(5))
        ];
    }
}