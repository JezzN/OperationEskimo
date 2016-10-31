<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 23/10/2016
 * Time: 23:53
 */

namespace App\OE\Forum;


use Illuminate\Database\Eloquent\Model;
use s9e\TextFormatter\Configurator;

class Post extends Model
{
    protected $connection = 'forums';

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renderContent()
    {
        return preg_replace('/(<e>|<s>).+(<\/s>|<\/e>)/', '', $this->content);
    }
}