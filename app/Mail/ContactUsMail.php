<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $email;
    public $messageReq;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
    $name,
    $email,
    $messageReq)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->messageReq =$messageReq;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@conterize.com')->view('contact-us');

    }
}