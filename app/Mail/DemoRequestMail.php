<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $content_types;
    public $industry;
    public $companysize;
    public $description;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $firstname,
     $lastname,
     $email,
     $phone,
     $content_types,
     $industry,
     $companysize,
     $description,)
    {
       $this->firstname = $firstname;
       $this->lastname = $lastname;
       $this->email = $email;
       $this->phone =$phone;
        $this->email_to =$content_types;
        $this->industry =$industry;
        $this->companysize =$companysize;
        $this->description =$description;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($email)->view('demo-request');
        // return $this->from("hello@conterize.com")->view('demo-request');
    }
}