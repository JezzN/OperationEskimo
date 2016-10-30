<?php
namespace App\OE\Configuration;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $casts = [
        'configuration' => 'array'
    ];
}