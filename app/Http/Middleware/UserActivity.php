<?php

namespace App\Http\Middleware;
use Auth;
use Cache;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        //this function runs at the before every request to check user is authenticated
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        // echo($user);
            if(Auth::check()){
                $expiresAt=now()->addMinutes(2);
                Cache::put('user-is-online-'.Auth::user()->id,true,$expiresAt);

                // User last seen
                User::where('id',Auth::user()->id)->update(['last_seen'=>now()]);
            }
        return $next($request);
    }
    public function terminate($request, $response)
    {
        // ...this function runs at the end of every request 
        
        $responseContent=$response->content();
        $responseContent = json_decode($responseContent, true);
        $token=$responseContent['token']?? null;
        $request->request->add(['token'=>$token]);//this converts back bycrypted to normal plain password so the user can login
        
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        // echo($user);
            if(Auth::check()){
                $expiresAt=now()->addMinutes(2);
                Cache::put('user-is-online-'.Auth::user()->id,true,$expiresAt);

                // User last seen
                User::where('id',Auth::user()->id)->update(['last_seen'=>now()]);
            }
        
    }
}