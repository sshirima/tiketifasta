<?php

namespace App\Console\Commands;

use App\Models\ScheduledTask;
use Illuminate\Console\Command;

class RegisterScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:scheduled-task 
    {name : Name of the task} 
    {--description=No description : Task description} 
    {--interval=1 : Running interval of the task} 
    {--unit=day : Units for the running interval of the task minute,hour,day,week,month,year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create scheduled task model for tracking scheduled task behaviour';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $uid = $this->gen_uuid();
        $name = $this->argument('name');
        $description = $this->option('description');
        $interval = $this->option('interval');
        $unit = $this->option('unit');

        $task = ScheduledTask::create([
            'task_uid'=>$uid,
            'task_name'=>$name,
            'task_description'=>$description,
            'run_interval'=>$interval,
            'interval_unit'=>$unit
        ]);

        $this->line('New task created: ID='.$task->task_uid);
        //$this->line($this->gen_uuid());
    }

    private function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
