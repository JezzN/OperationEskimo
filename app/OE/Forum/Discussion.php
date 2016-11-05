<?php
namespace App\OE\Forum;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $connection = 'forums';

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'discussions_tags');
    }
}