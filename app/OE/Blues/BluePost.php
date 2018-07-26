<?php
/**
 * Created by PhpStorm.
 * User: jeremy.norman
 * Date: 26/07/2018
 * Time: 07:52
 */

namespace App\OE\Blues;


use Illuminate\Database\Eloquent\Model;

class BluePost extends Model
{
    public function getLink()
    {
        return "https://worldofwarcraft.com/en-us/news/{$this->topic_id}";
    }
}