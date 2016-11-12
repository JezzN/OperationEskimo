<?php
namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class GuildMemberChange extends Model
{
    public static function fromEventString($characterName, $event)
    {
        $change = new static();
        $change->character_name = $characterName;
        $change->event = $event;
        $change->save();
    }
}