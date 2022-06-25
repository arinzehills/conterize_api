<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;
use App\Models\User;
use Validator;
use JWTAuth;
use Auth;

use App\Mail\FirstEmail;

class MyTeamMemberController extends Controller
{
    //
    public function show(Request $request)
    {
        $teamModel = config('teamwork.team_model');
        // $team_members = $teamModel::find($request->id);
        $team_members = $teamModel::with(['users','invites'])
        ->where(['id'=>$request->id])->get();
        
        if (is_null($team_members)) {
            return response()->json([
                'status' => 'ERROR',
                'error' => '404 not found'
            ], 404);
        }
        // $team_members->;
        return $team_members;
        // return $team_members->users;
    }
    //delete member and remove from team
    public function destroy(Request $request)
    {   
        $rules = ['team_id'=>'required','user_id'=>'required',];
         $validator = Validator::make($request->all(), $rules);
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        
         if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $team_id=$request->team_id;
         $user_id=$request->user_id;//id of the resource to delete

        $teamModel = config('teamwork.team_model');
        $team = $teamModel::find($team_id);
        if(is_null($team)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => '404 team not found'
            ], 404);
        }
        if (! auth()->user()->isOwnerOfTeam($team)) {
            return response()->json([
                'success' => false,
                'message' => 'UnAuthorized! Dont have permission to delete the resource'
            ], 403);
        }

        $userModel = config('teamwork.user_model');
        $user = $userModel::find($user_id);
        if(is_null($user)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => '404 user not found'
            ], 404);
        }
        if ($user->getKey() === auth()->user()->getKey()) {
            //If the user requesting deletion is equal to user to be deleted
            //abort 
            return response()->json([
                'success' => false,
                'message' => "UnAuthorized! Can't delete your own team"
            ], 403);
        }
        $user->detachTeam($team);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "User has been deleted"
        ], 403);
    }
    public function invite(Request $request)
    {   
        $rules = ['token' => 'required',];
        $validator = Validator::make($request->all(), $rules);
       if ($validator->fails()) {
           // handler errors
           $erros = $validator->errors();
           return $erros;
        }
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
       
        $team_id=$user->current_team_id;
        $request->request->add(['team_id'=>$team_id]);
        
        $rules = ['email' => 'unique:users,email|required','team_id' => 'required',];
         $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            return $erros;
         }
         
         $team_id=$request->team_id;
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        $user_id=$request->user()->getKey();

        if (! Teamwork::hasPendingInvite($request->email, $team)) {
            Teamwork::inviteToTeam($request,$user_id, $team, function ($invite) {//i added user_id to this function as param but did not use it
                Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                    $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
                });
                // Send email to user
            });
        } else {
          return
            response()->json([
                'success'=>false,
                'message' => 'The email address is already invited to the team. Resend invite instead',
        ], 422);
        }

        return 
                response()->json([
                        'success'=>true,
                        'message'=>'Email invitation sent',
                    'teams_members'=>$team
                ], 422);
        // redirect(route('teams.members.show', $team->id));
    }
    public function resendInvite(Request $request)
    {
        $rules = ['invite_id'=>'required',];
         $validator = Validator::make($request->all(), $rules);
       
         if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $invite_id=$request->invite_id;
        $invite = TeamInvite::find($invite_id);
        if (is_null($invite)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => '404 not found'
            ], 404);
        }
        Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
            $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
        });

        return  response()->json([
            'success'=>true,
            'message'=>'Email invitation sent',
                 ], 422);
    }
    public function denyInvite(Request $request){
        $rules = ['invite_id'=>'required',];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $invite_id=$request->invite_id;
         $invite = TeamInvite::find($invite_id);
        if (is_null($invite)) {
            return response()->json([
                'success' => false,
                'message' => 'invitation not found'
            ], 404);
        }
        $delete_invite=Teamwork::denyInvite($invite);
        return response()->json([
            'success' => true,
            'message' => 'Invitation has been deleted'
        ], 403);

    }
    public function acceptInvite(Request $request)
    {   
        $rules = ['invite_token'=>'required ','password'=>'required|confirmed '];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $invite_token=$request->invite_token;
        $invite = Teamwork::getInviteFromAcceptToken($invite_token);
        // echo $invite;
        if (! $invite) {
            return response()->json([
                'success' => false,
                'error' => '404 not found',
                'token'=>''
            ], 404);
        }
        return $this->SignUpInvitee($request,$invite);
       
    }
    public function SignUpInvitee(Request $request,$invite){
        $firstname=$invite->firstname;
        $lastname=$invite->lastname;
        $email=$invite->email;
        $plainPassword=$request->password;
        $password=bcrypt($request->password);
        $request->request->add(['password'=>$password]);
        $request->request->add(['email'=>$email]);
        $rules = ['email'=>'unique:users,email|required',];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            // handler errors
            $erros = $validator->errors();
            // echo $erros;
            return $erros;
         }
        $created=User::create($request->all()+[
                                'firstname'=>$firstname,
                                'lastname'=>$lastname,]);
        $request->request->add(['password'=>$plainPassword]);//this converts back bycrypted to normal plain password so the user can login

        //     //login and get the user
             $input=[
                'email'=>$email,'password'=>$request->password];
             
            $jwt_token=null;
            if(!$jwt_token= JWTAuth::attempt($input)){
                return response()->json([
                    'message'=> 'Invalid email or password',
                    'success'=>false,
                ], 401);
            }
            //get the user
        $user=Auth::user();
        if (auth()->check()) {//if user is registered and is login
            Teamwork::acceptInvite($invite,$user);
            return response()->json([
                'success'=>true,
                'user'=>$user,
                'token'=> $jwt_token,
                'message'=>'User attached to a team',
                     ], 422);   
        }else{
            return response()->json([
                'success'=>false,
                'user'=>$user,
                'token'=>'',
                'message'=>'No registerd user',
                     ], 422);
            }
    }
   
}