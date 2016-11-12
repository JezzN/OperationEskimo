<?php
namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    public function rankName()
    {
        $ranks = config('operation-eskimo.ranks');

        return $ranks[$this->rank];
    }

    public function getFriendlySpecName()
    {
        if( ! $this->spec ) return '?';

        return ucfirst($this->spec);
    }
}