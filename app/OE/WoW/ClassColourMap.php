<?php
namespace App\OE\WoW;

class ClassColourMap
{
    private static $map = [
        'warrior' => 'C79C6E',
        'paladin' => 'F58CBA',
        'hunter' => 'ABD473',
        'rogue' => 'FFF569',
        'priest' => 'FFFFFF',
        'death knight' => 'C41F3B',
        'shaman' => '0070DE',
        'mage' => '69CCF0',
        'warlock' => '9482C9',
        'monk' => '00FF96',
        'druid' => 'FF7D0A',
        'demon hunter' => 'A330C9',
    ];

    private static $default = 'fff';

    public static function nameToColour($name)
    {
        $name = strtolower($name);

        if( !isset(static::$map[$name]) ) return static::$default;

        return static::$map[$name];
    }
}