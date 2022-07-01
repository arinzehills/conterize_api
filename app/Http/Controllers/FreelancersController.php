<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Request as RequestModel;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendToFreelacer;
use JWTAuth;
use Auth;

class FreelancersController extends Controller
{
    public function addFreelancer(Request $request){

           $to_email=$request->email;
           $plainPassword='123'.Str::random(2);
           $password=bcrypt($plainPassword);
            $request->request->add(['password'=>$password]);
           $request->request->add(['user_type'=>'content_creator']);
        //    $request->request->add(['password'=>$plainPassword]);
           $rules = ['email'=>'unique:users,email|required','firstname'=>'required',
           'lastname'=>'required'];
            $validator = Validator::make($request->all(), $rules);
            // create the user account
            if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
            }else{
            $created=User::create($request->all());
            $request->request->add(['password'=>$plainPassword]);//this converts back bycrypted to normal plain password so the user can login
            //login now..
            $input =$request->only('email','password');
            $jwt_token=null;
            if(!$jwt_token= JWTAuth::attempt($input)){
            return response()->json([
                'message'=> 'Invalid email or password',
                'success'=>false,
                'token'=>null
            ], 401);
        }
        //get the user
        $user=Auth::user();
           //send email and password
            $sendmail=Mail::to($to_email)->send(new SendToFreelacer($to_email,$plainPassword));
        
            if(Mail::failures() != 0) {
                return response()->json([
                    'success'=>true,      
                    'message'=>"Success! Your E-mail has been sent.",
                    'token'=> $jwt_token,
            'user'=> $user,    
                ], 200);
            }

            return response()->json([
                'success'=>false,      
                'message'=>"Failed! Your E-mail has not sent.", 
                 
            ], 404);
      
        }

    }
   
    //
    public function getAllFreelancers(Request $request){
        $freelancers=User::Join('content_creators', 'users.id', '=', 'content_creators.user_id')
            ->where('user_type','content_creator')
            ->get();
            return $freelancers;
    }
    public function assignFreelancer(Request $request){
        $request_id=$request->id;
        $freelancer_name=$request->freelancer_name;
        $user_request =  RequestModel::find($request->id);
        $user_request ->assign_to=$freelancer_name;
        $user_request->save();

        return response()->json([
            'success' => true, 
            'message' => 'Password has been updated successfully!',
           'user_request'=> $user_request]);
    }
}