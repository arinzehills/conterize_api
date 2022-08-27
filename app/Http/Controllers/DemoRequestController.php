<?php

namespace App\Http\Controllers;
use App\Models\DemoRequest;

use Illuminate\Support\Facades\Mail;
use App\Mail\FirstEmail;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    //
    public function requestDemo(Request $request){
    $created=DemoRequest::create($request->all());
    if($created){
        $to_email = $request->email;

        Mail::to($to_email)->send(new FirstEmail);
        Mail::to('hello@conterize.com')->send(new FirstEmail);

        if(Mail::failures() != 0) {
            return "<p> Success! Your demo reques has been sent $to_email.</p>";
        }

        else {
            return "<p> Failed! Your E-mail has not sent.</p>";
        }
        }
    }
}