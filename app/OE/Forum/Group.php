<?php
namespace App\OE\Forum;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $connection = 'forums';

    public function users()
    {
        return $this->belongToMany(User::class, 'users_groups');
    }
}