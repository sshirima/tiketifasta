<?php

namespace App\Domain\NotifyUsers\Mail;

use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AvailabilityStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $parameters;

    public function __construct($view, $parameters)
    {
        $this->view = $view;
        $this->parameters = $parameters;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown($this->view)->subject($this->parameters['subject']);
    }
}
