<?php
namespace App\OE\Discord\Bot\Commands;

use App\Http\Controllers\ArtifactController;
use App\OE\Configuration\Configuration;
use App\OE\Configuration\RosterConfiguration;
use App\OE\Discord\Bot\Command;
use App\OE\OperationEskimo;
use App\OE\WoW\Artifact;
use Discord\Parts\Channel\Message;

class Dangerzone extends Command
{
    /** @var OperationEskimo */
    private $operationEskimo;
    /** @var RosterConfiguration */
    private $configuration;

    public function __construct(OperationEskimo $operationEskimo, RosterConfiguration $configuration)
    {
        $this->operationEskimo = $operationEskimo;
        $this->configuration = $configuration;
    }

    public function execute(Message $message)
    {
        if( in_array($message->channel->name, ['officerchat', 'bottest']) ) {
            if( trim($message->content) !== '!dangerzone' ) {
                return $this->updateDangerzone($message);
            }
        }

        $dangerzone = Artifact::getDangerzone();

        $reply = "Players in the danger zone (below {$dangerzone}) are:" . PHP_EOL . PHP_EOL;

        foreach( ArtifactController::compiledArtifacts() as $artifact ) {

            if( ! $artifact->isInDangerZone() || $artifact->offspec || in_array(strtolower($artifact->member->character_name), $this->configuration->getExcluded()) ) continue;


            $reply .= "**{$artifact->member->character_name} ({$artifact->getSpec()})** is rank {$artifact->rank}" . PHP_EOL . PHP_EOL;
        }


        $message->channel->sendMessage($reply);
    }

    private function updateDangerzone(Message $message)
    {
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

        $message->channel->sendMessage("Updated dangerzone to {$newDangerzone}");
    }

    protected function invalidDangerzone(Message $message)
    {
        return $message->channel->sendMessage("{$message->content} is not valid");
    }
}