<?php
namespace App\OE\Configuration;

class RecruitmentConfiguration
{
    /** @var ConfigurationLoader */
    private $configuration;

    const RECRUITMENT_CLOSED = 'closed', RECRUITMENT_LOW = 'low', RECRUITMENT_OPEN = 'medium', RECRUITMENT_HIGH = 'high';

    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configuration = $configurationLoader->get('recruitment');
    }

    public function open()
    {
        $open = [];

        foreach( $this->configuration as $class => $specs ) {
            if( ! is_array($specs) ) {
                if( $this->isRecruitmentOpen($specs) ) $open[] = $this->createRecruitmentArray($class, $specs);
                continue;
            }

            foreach( $specs as $spec => $status ) {
                if( $this->isRecruitmentOpen($status) ) $open[] = $this->createRecruitmentArray($class, $status, $spec);
            }
        }

        return $open;
    }

    private function createRecruitmentArray($class, $status, $spec = null)
    {
        return [
            'friendly_name' => $this->createRecruitmentName($class, $spec),
            'class' => $class,
            'spec' => $spec,
            'status' => $status,
        ];
    }

    private function isRecruitmentOpen($status)
    {
        return $status !== static::RECRUITMENT_CLOSED;
    }

    private function createRecruitmentName($class, $spec = null)
    {
        $class = ucfirst($class);

        if( empty($spec) ) return $class;

        $spec = ucfirst($spec);

        return "{$class} ({$spec})";
    }
}