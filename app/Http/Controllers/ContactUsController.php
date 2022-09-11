<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    //
    public function contactUs(Request $request){
  
            $name = $request->name;
            $email = $request->email;
            $message = $request->message;
            // Mail::to($to_email)->send(new FirstEmail);
            Mail::to('arinzehill@gmail.com')->send(new DemoRequestMail(
                $name,
            $email,
            $message));
    
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