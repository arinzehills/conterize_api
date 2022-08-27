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

        $to_email = "achills.business@gmail.com";

        Mail::to($to_email)->send(new FirstEmail);

        if(Mail::failures() != 0) {
            return response()->json([
                'success'=>true,      
                'message'=>"Success! Demo request has been sent.",
            ], 200);
        }

        else {
            return response()->json([
                'success'=>false,      
                'message'=>"Failed! Your E-mail has not sent.", 
            ], 404);
        }
    }
}