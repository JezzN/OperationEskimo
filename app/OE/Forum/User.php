<?php
namespace App\OE\Forum;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'forums';

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups');
    }
}