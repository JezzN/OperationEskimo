<?php
namespace App\OE\Discord\Bot\Commands;

use App\OE\Discord\Bot\Command;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Voice\VoiceClient;

class PlayFile extends Command
{
    public function execute(Message $message, Discord $discord)
    {
        $discord->joinVoiceChannel('230750838459465740')->then(function(VoiceClient $vc) {
            $vc->playFile(storage_path('audio/me.mp3'));
            $vc->close();
        });
    }
}