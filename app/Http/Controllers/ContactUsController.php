<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUsMail;

class ContactUsController extends Controller
{
    //
    public function contactUs(Request $request){
  
            $name = $request->name;
            $email = $request->email;
            $message = $request->message;
            // Mail::to($to_email)->send(new FirstEmail);
            Mail::to('hello@conterize.com')->send(new ContactUsMail(
                $name,
            $email,
            $message,));
    
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