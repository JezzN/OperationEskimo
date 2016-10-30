<?php

namespace App\Console\Commands;

use App\OE\Configuration\ConfigurationLoader;
use Illuminate\Console\Command;

class ParseForumConfigurationThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guild:parse-configurations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the forum threads for any configuration options.';

    /** @var ConfigurationLoader */
    private $configurationParser;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ConfigurationLoader $configurationParser)
    {
        parent::__construct();
        $this->configurationParser = $configurationParser;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->configurationParser->parseAll();
    }
}
