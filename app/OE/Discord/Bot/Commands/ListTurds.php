<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Parts\Channel\Message;

class ListTurds extends Command
{

    public function execute(Message $message)
    {
        $reply = "Turds are:" . PHP_EOL . PHP_EOL;

        $turds = [
            'Skuggmeester',
            'Haleena',
            'Daedalia'
        ];

        $i = 1;
        foreach( $turds as $turd ) {
            $reply .= "#{$i} {$turd}" . PHP_EOL . PHP_EOL;
            $i++;
        }
    }
}