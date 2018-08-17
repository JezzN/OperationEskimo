<?php

namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class CharacterRep extends Model
{
    public function getStanding()
    {
        switch($this->standing) {
            case 0: return 'Hated';
            case 1: return 'Hostile';
            case 2: return 'Unfriendly';
            case 3: return 'Neutral';
            case 4: return 'Friendly';
            case 5: return 'Honored';
            case 6: return 'Revered';
            case 7: return 'Exalted';
        }
    }
}