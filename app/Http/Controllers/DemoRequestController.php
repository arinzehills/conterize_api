<?php

namespace App\Http\Controllers;
use App\Models\DemoRequest;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemoRequestMail;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    //
    public function requestDemo(Request $request){
    $created=DemoRequest::create($request->all());
    // if($created){
    //     return response()->json([
    //                     'success'=>true,      
    //                     'message'=>"Suzzess! Your E-mail sent.", 
    //                 ], 404);
    // }
    if($created){
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $phone = $request->phone;
        $content_types = $request->content_types;
        $industry = $request->industry;
        $companysize = $request->companysize;
        $description = $request->description;
        $content_types_solved= implode(', ', $content_types);
        // Mail::to($to_email)->send(new FirstEmail);
        Mail::to('hello@conterize.com')->send(new DemoRequestMail($firstname,
        $lastname,
        $email,
        $phone,
        $content_types_solved,
        $industry,
        $companysize,
        $description,));

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
    public function getAllDemo(Request $request){
        $demos=DemoRequest::all();

        return $demos;
    }
}