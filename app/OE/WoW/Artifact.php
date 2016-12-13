<?php
namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
    public function member()
    {
        return $this->belongsTo(GuildMember::class, 'member_id');
    }
}