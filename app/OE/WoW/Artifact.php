<?php
namespace App\OE\WoW;

use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
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
}