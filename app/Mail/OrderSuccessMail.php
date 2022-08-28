<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request_name;
    public $request_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request_name,$request_type)
    {
        //
        $this->request_name = $request_name;
       $this->request_type= $request_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("hello@conterize.com")->view('order-successful');
    }
}