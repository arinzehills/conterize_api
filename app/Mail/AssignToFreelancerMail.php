<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignToFreelancerMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request_name)
    {
        //
        $this->request_name = $request_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from("admin@conterize.com")->view('assign_to_freelancer');

    }
}