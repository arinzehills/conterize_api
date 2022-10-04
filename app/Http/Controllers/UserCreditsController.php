<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\UserCredits;

class UserCreditsController extends Controller
{
    //

    public function getUserCredits(Request $request){
        $id=$request->user_id;
        $rules = ['user_id'=>'required:requests,user_id'];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
                $user_credits=UserCredits::
                where('user_id',$id)
                ->get();
                return response()->json([
                    'success'=>true,
                    'message'=>'Your request has been placed successfully',
                    'user_credits'=>$user_credits[0],
                ]);
            }
    }
}