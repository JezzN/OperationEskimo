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

    public function user()
    {
        return $this->belongsTo(User::class, 'start_user_id');
    }

    public function url()
    {
        return config('operation-eskimo.forum-link') . 'd/' . $this->id . '-' . $this->slug;
    }
}