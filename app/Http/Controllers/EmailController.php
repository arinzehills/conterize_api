<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\FirstEmail;
class EmailController extends Controller
{
    //
    public function index(){
    return view('teamwork.emails.invite');
        // return view('email-template');
        // ->withTeam($team);

    }
    public function sendEmail() {

        $to_email = "arinzehill@gmail.com";

        Mail::to($to_email)->send(new FirstEmail);

        if(Mail::failures() != 0) {
            return "<p> Success! Your E-mail has been sent.</p>";
        }

        else {
            return "<p> Failed! Your E-mail has not sent.</p>";
        }
    }
}