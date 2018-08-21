<?php

namespace App\Jobs;

use App\Mail\RegisterMerchantMail;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisterMerchantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $merchant;
    public $password;

    public function __construct(Merchant $merchant, Staff $account, $password)
    {
        $this->account = $account;
        $this->merchant = $merchant;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::to($this->account)->send(new RegisterMerchantMail($this->merchant, $this->account, $this->password));
    }
}
