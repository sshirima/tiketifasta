<?php

namespace App\Console\Commands;

use App\Domain\Admin\MonitorSystem\MonitorSystemAggregate;
use App\Domain\NotifyUsers\NotifyUsersAggregate;
use App\Models\Day;
use App\Services\Schedules\SchedulesAuthorization;
use Illuminate\Console\Command;

class NotifyUserBySMS extends Command
{
    use SchedulesAuthorization;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms  
    {recipient : Number of the recipient to receive the message} 
    {message : Content of the message} 
    {operator : Telecom operator recipient belongs to, Vodacom or Tigo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to telecom numbers';
    protected $notifyUserAggregate;


    public function __construct(NotifyUsersAggregate $notifyUsersAggregate )
    {
        parent::__construct();
        $this->notifyUserAggregate = $notifyUsersAggregate;
    }

    /**
     * Disable expired schedules
     */
    public function handle()
    {
        $recipient = $this->argument('recipient');
        $message = $this->argument('message');
        $operator = $this->argument('operator');

        $status = $this->notifyUserAggregate->sendSMSToOne($recipient,$message,$operator);
        $this->info(json_encode($status));
    }
}
