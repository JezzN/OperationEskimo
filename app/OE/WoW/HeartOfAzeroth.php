<?php

namespace App\OE\WoW;

use App\OE\WowProgress\Character;
use Illuminate\Database\Eloquent\Model;

class HeartOfAzeroth extends Model
{
    protected $table = 'character_heart_of_azeroth';

    public static function hallOfFame($amount = 5) {
        return static::orderBy('level', 'desc')->orderBy('experience', 'desc')->take($amount)->get();
    }

    public static function hallOfShame($amount = 5) {
        return static::orderBy('level', 'asc')->orderBy('experience', 'asc')->take($amount)->get();
    }

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

    public function getPercentageIntoLevel() {
        return floor(($this->experience / $this->experience_remaining) * 100);
    }
}