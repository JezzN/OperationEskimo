<?php
namespace App\OE\Discord\Bot\Commands;

use App\Http\Controllers\ArtifactController;
use App\OE\Configuration\Configuration;
use App\OE\Configuration\RosterConfiguration;
use App\OE\Discord\Bot\Command;
use App\OE\OperationEskimo;
use App\OE\WoW\Artifact;
use App\OE\WoW\HeartOfAzeroth;
use Discord\Parts\Channel\Message;

class Dangerzone extends Command
{
    /** @var OperationEskimo */
    private $operationEskimo;

    /** @var RosterConfiguration */
    private $configuration;

    protected $description = "Show raiders that are not at the required artifact level";

    public function __construct(OperationEskimo $operationEskimo, RosterConfiguration $configuration)
    {
        $this->operationEskimo = $operationEskimo;
        $this->configuration = $configuration;
    }

    public function execute(Message $message)
    {
        if( $message->channel->id == config('operation-eskimo.discord-channel-officer') ) {
            if( trim($message->content) !== '!dangerzone' ) {
                return $this->updateDangerzone($message);
            }
        }

        $dangerzone = Configuration::where('category', 'dangerzone')->first()->configuration['level'];

        $reply = "Players in the danger zone (below {$dangerzone}) are:" . PHP_EOL . PHP_EOL;

        foreach( HeartOfAzeroth::orderBy('level', 'desc')->orderBy('experience', 'desc')->get() as $artifact ) {

            if( $artifact->level >= $dangerzone || in_array(strtolower($artifact->character_name), $this->configuration->getExcluded()) ) continue;


            $reply .= "**{$artifact->character_name}** is rank {$artifact->level}" . PHP_EOL . PHP_EOL;
        }


        $message->channel->sendMessage($reply);
    }

    private function updateDangerzone(Message $message)
    {
        $originalDangerzone = Configuration::where('category', 'dangerzone')->first()->configuration['level'];
        preg_match('/[0-9]{1,3}$/', $message->content, $matches);

        if( empty($matches) ) {
            return $this->invalidDangerzone($message);
        }

        $newDangerzone = $matches[0];

        if( ! is_numeric($newDangerzone) ) {
            return $this->invalidDangerzone($message);
        }

        $config = Configuration::where('category', 'dangerzone')->first();
        $config->configuration = ['level' => (int) $newDangerzone];
        $config->save();

        $message->channel->sendMessage("Danger zone has been changed from {$originalDangerzone} to {$newDangerzone}");
    }

    protected function invalidDangerzone(Message $message)
    {
        return $message->channel->sendMessage("{$message->content} is not valid");
    }
}