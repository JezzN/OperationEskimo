<?php

namespace App\OE\WoW;

use App\OE\WowProgress\Character;
use Illuminate\Database\Eloquent\Model;

class HeartOfAzeroth extends Model
{
    protected $table = 'character_heart_of_azeroth';

    public function character() {
        return $this->belongsTo(GuildMember::class,'character_name', 'character_name');
    }

    public function reputation() {
        return $this->hasMany(CharacterRep::class, 'character_name', 'character_name');
    }

    public function championsOfAzeroth() {
        return $this->reputation->where('reputation_name', 'Champions of Azeroth')->first();
    }

    public function honorbound() {
        return $this->reputation->where('reputation_name', 'The Honorbound')->first();
    }
}