<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ContentCreators;
use Cache;
use Auth;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;


use Validator;

class UserController extends Controller {
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }
    public function index(Request $request){
        $users = User::all();
        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id))
                echo $user->name . " is online. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
            else
                echo $user->name . " is offline. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
        }
            // $users=User::
            // // All()
            // // select('*')
            //     orderBy('last_seen','Desc')
            //         ->whereNotNull('last_seen')
            //         ->get()
            //         ;

                    return $users;
        }
    public function register(Request $request){
        $email=$request->email;
        $plainPassword=$request->password;
        $password=bcrypt($request->password);
        $request->request->add(['password'=>$password]);
        // echo $request->email;
        $rules = ['email'=>'unique:users,email|required','firstname'=>'required',
                    'lastname'=>'required'];
         $validator = Validator::make($request->all(), $rules);
        // echo $validator;

        // create the user account
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $created=User::create($request->all());
            if($request->user_type=='content_creator'){
                $created=ContentCreators::create(['user_id'=>$created->getKey(),'activated'=>'no',]+$request->all());
            }
            $request->request->add(['password'=>$plainPassword]);//this converts back bycrypted to normal plain password so the user can login
        //login now..
        return $this->login($request);
         }
        

    }

    public function login(Request $request){
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

        // attach/update user automatically to a team
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::updateOrCreate(['id'=>$user->id],[
            'name' => $user->firstname ,
            'owner_id' => $user->getKey(),
        ]);
        $user->attachTeam($team);

        return response()->json([
            'success'=>true,      
            'message'=>'user logged in successfully!',   
            'token'=> $jwt_token,
            'user'=> $user,
        ], 422);
    }
    public function logout(Request $request){
        if(!User::checkToken($request)){
            return response()->json([
                'message'=> 'Token is required',
                'success'=>false,
            ], 422);
        }
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'message'=> 'user logged out successfully!',
                'success'=>true,
            ], 500);
        }catch(JWTException $exception){

            return response()->json([
                'message'=> 'Sorry user cannot be logged out!',
                'success'=>false,
            ], 500);
        }
}

        public function getCurrentUser(Request $request){
            if(!User::checkToken($request)){
                
                return response()->json([
                    'message'=> 'Token is required',
                ], 422);
            }
        
        $user=JWTAuth::parseToken()->authenticate();
        //add is profileUpdated...
        $isProfileUpdated=false;
        if($user->isPicUpdated==1 && $user->isEmailUpdated){
            $isProfileUpdated=true;
        }
        $user->isProfileUpdated= $isProfileUpdated;

         return $user;
    }

    public function update(Request $request){
        $user=$this->getCurrentUser($request);
        // echo $user; 
        $data=$request->all();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'User is not found'
            ]);
        }
       
        // echo($data['token']);
        unset($data['token']);
        
        // echo($request->id);
        $updatedUser = User::where('id', $user->id)->update($data);
        $user =  User::find($user->id);
        
        return response()->json([
            'success' => true, 
            'message' => 'Information has been updated successfully!',
            'user' =>$user
        ]);
    }
    public function forgotPassword(Request $request){
        // $request->validate(['email' => 'required|email']);
        
        $email = $request->only('email');
        $rules = ['email'=>'required:users,email'];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
             $user = User::where('email', '=', $email)->first();
             try { 
                 // verify the credentials and create a token for the user
                 if (! $token = JWTAuth::fromUser($user)) { 
                     return response()->json(['error' => 'invalid_credentials'], 401);
                 } 
             } catch (JWTException $e) { 
                 // something went wrong 
                 return response()->json(['error' => 'could_not_create_token'], 500); 
             } 
             // if no errors are encountered we can return a JWT 
        //  return response()->json(compact('token')); 

            $status = Password::sendResetLink($email);
        
            return $status === Password::RESET_LINK_SENT
                    ? response()->json(['status' => $status])
                    : response()->json(['status' => $status]);

         }

    }
    public function resetPassword(Request $request)
     {   
        // $this->validate($request, [
        //         'token' => 'required',
        //         'email' => 'required|email',
        //         'password' => 'required|confirmed',
        // ]); 
        $rules = ['email'=>'required:users,email','password' => 'required|confirmed',
                    'token'=>'required  '];
         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
            $credentials = $request->only(
                    'email', 'password', 'password_confirmation', 'token'
            );  
            // $response = $request->password->reset($credentials, function($user, $password) {
            //         $user->password = bcrypt($password);
            //         $user->save();
            //         $this->auth->login($user);
            // }); 
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    // $user->forceFill([
                    //     'password' => Hash::make($password)
                    // ])->setRememberToken(Str::random(60));
                    $user->password = bcrypt($password);
                    $user->save();
         
                    event(new PasswordReset($user));
                }
            );
         
            return $status === Password::PASSWORD_RESET
                        ? response()->json(['status', __($status)])
                        :response()->json(['email' => __($status)]);
            return json_encode($response);
            

         }
     } 

     public function updatePassword(Request $request)
     {   
        // $this->validate($request, [
        //         'token' => 'required',
        //         'email' => 'required|email',
        //         'password' => 'required|confirmed',
        // ]); 
        $rules = ['oldpassword'=>'required:users,oldpassword','password' => 'required|confirmed',
                    'token'=>'required','id'=>'required'];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }else{
             $user =  User::find($request->id);
            //  echo $user->password;
            //  echo $request->oldpassword;
            //  echo bcrypt($user->password);
            if(strcmp($request->get('oldpassword'), $request->get('password')) == 0){
                //Current password and new password are same
                return response()->json([
                    'success' => false, 
                    'message' => 'New Password cannot be same as your current password. Please choose a different password.',
                ]);
            }
            if(Hash::check($request->oldpassword,$user->password)){
                $user->password = bcrypt($request->password);
                $user->save();
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Password has been updated successfully!',
                    'user'=>$user,
                ]);
                
                // $status === Password::PASSWORD_RESET
                //         ? response()->json(['status', __($status)])
                //         :response()->json(['email' => __($status)]);
            }else{
                return response()->json([
                    'success' => false, 
                    'message' => 'Your current password does not matches with the old password you provided.',                    
                ]);

            }

          
            }
        }

    }