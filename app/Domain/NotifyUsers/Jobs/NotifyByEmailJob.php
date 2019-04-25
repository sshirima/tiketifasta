<?php

namespace App\Domain\NotifyUsers\Job;

use App\Mail\RegisterMerchantMail;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyByEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailContent;
    public $recipientMailAddress;

    public function __construct($recipientMailAddress, $emailContent)
    {
        $this->emailContent = $emailContent;
        $this->recipientMailAddress = $recipientMailAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::to($this->recipientMailAddress)->send($this->emailContent);
    }
}
