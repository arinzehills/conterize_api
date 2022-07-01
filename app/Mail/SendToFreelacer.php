<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendToFreelacer extends Mailable
{
    use Queueable, SerializesModels;

    public $email_to;
    public $plainPassword;
    /**
     * Create a new message instance.
     *
     * @return void
     */
   
    public function __construct($email_to,$plainPassword)
    {
        $this->email_to = $email_to;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("admin@conterize.com")->view('send-pass-to-freelancer');
        // ->with([
        //     'plainPassword' => $this->plainPassword,
        //     'email' => $this->email_to,
        // ]);

    }
}