<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubSuccessMail extends Mailable
{
    use Queueable, SerializesModels;
    public $plan_name;
    public $price;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($plan_name,$price)
    {
        //
        $this->plan_name = $plan_name;
       $this->price = $price;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("hello@conterize.com")->view('subcription-success');
    }
}