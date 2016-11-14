<?php
namespace App\OE\WarcraftLogs;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    private $percentileBrackets = [
        90, 80, 50, 25, 0
    ];

    public function adjustedPercentile()
    {
        return $this->percentile >= 99 ? 99 : $this->percentile;
    }

    public function getPercentileBracket()
    {
        foreach( $this->percentileBrackets as $bracket ) {
            if( $this->percentile >= $bracket ) return $bracket;
        }

        return 0;
    }
}