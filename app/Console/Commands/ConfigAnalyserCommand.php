<?php

namespace App\Console\Commands;

use App\Services\ConfigAnalyser;
use Illuminate\Console\Command;

class ConfigAnalyserCommand extends Command
{
    use ConfigAnalyser;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config-analyser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cisco routers configuration analyser';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->analyseConfigAll();
        $this->info('Exist');
    }
}
