<?php
namespace App\OE\Configuration;

class RosterConfiguration
{
    /** @var ConfigurationLoader */
    private $configuration;

    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configuration = $configurationLoader->get('roster');
    }


    public function isExcluded($characterName)
    {
        return in_array(strtolower($characterName), $this->getExcluded());
    }

    public function getExcluded() : array
    {
        return array_map(function($excluded) {
            return strtolower($excluded);
        }, $this->configuration['exclude']);
    }

    public function getRole($characterName)
    {
        $characterName = strtolower($characterName);

        if( ! isset($this->configuration['roles'][$characterName]) ) return;

        return strtolower($this->configuration['roles'][$characterName]);
    }
}