<?php
namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
    protected $artifactSpecMap = [
        'Twinblades of the Deceiver' => 'Havoc',
        'Doomhammer' => 'Enhancement',
        'Sheilun, Staff of the Mists' => 'Mistweaver',
        'Maw of the Damned' => 'Blood',
        'Blades of the Fallen Prince' => 'Frost',
        'Apocalypse' => 'Unholy',
        'Aldrachi Warblades' => 'Vengeance',
        'Scythe of Elune' => 'Balance',
        'Fangs of Ashamane' => 'Feral',
        'Claws of Ursoc' => 'Guardian',
        "G'Hanir, the Mother Tree" => 'Restoration',
        'Titanstrike' => 'Beast Mastery',
        "Thas'dorah, Legacy of the Windrunners" => 'Marksmanship',
        'Talonclaw' => 'Survival',
        'Aluneth' => 'Arcane',
        "Felo'melorn"  => 'Fire',
        'Ebonchill' => 'Frost',
        'Fu Zan, the Wanderer\'s Companion' => 'Brewmaster',
        'Sheilun, Staff of the Mists' => 'Mistweaver',
        'Al\'burq' => 'Windwalker',
        'The Silver Hand' => 'Holy',
        'Truthguard' => 'Protection',
        'Ashbringer' => 'Retribution',
        'Light\'s Wrath' => 'Discipline',
        'T\'uure, Beacon of the Naaru' => 'Holy',
        'Xal\'atath, Blade of the Black Empire' => 'Shadow',
        'The Kingslayers' => 'Assassination',
        'The Dreadblades' => 'Outlaw',
        'Fangs of the Devourer' => 'Subtlety',
        'The Fist of Ra-den' => 'Elemental',
        'Doomhammer' => 'Enhancement',
        'Sharas\'dal, Scepter of Tides' => 'Restoration',
        'Ulthalesh, the Deadwind Harvester' => 'Affliction',
        'Skull of the Man\'ari' => 'Demonology',
        'Scepter of Sargeras' => 'Destruction',
        'Strom\'kar, the Warbreaker' => 'Arms',
        'Warswords of the Valarjar' => 'Fury',
        'Scale of the Earth-Warder' => 'Protection',
    ];

    public function member()
    {
        return $this->belongsTo(GuildMember::class, 'member_id');
    }

    public function getColour()
    {
        if( $this->offspec ) return 'CCCCCC';

        if( $this->rank < 35 ) return 'E80505';

        return '00BD06';
    }

    public function getSpec()
    {
        if( ! isset($this->artifactSpecMap[$this->artifact_name]) ) return $this->artifact_name;

        return $this->artifactSpecMap[$this->artifact_name];
    }
}