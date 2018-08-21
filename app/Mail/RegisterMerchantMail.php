<?php

namespace App\Mail;

use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterMerchantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $account;
    public $merchant;
    public $password;

    public function __construct(Merchant $merchant, Staff $account, $password)
    {
        $this->account = $account;
        $this->merchant = $merchant;
        $this->password =$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.merchants.registered')->subject('Account registration');
    }
}
