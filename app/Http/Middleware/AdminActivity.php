<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
class AdminActivity
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
        $rules = ['token'=>'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        // handler errors
        $errors = $validator->errors();
        // return $erros;
        return new JsonResponse($errors, 422);;
        }
        $user=app('App\Http\Controllers\UserController')->getCurrentUser($request);
        $decodeUser = json_decode($user, true);
        $user_type=$decodeUser['user_type'];

       if($user_type!='admin'){
        return response()->json([
            'success'=>false,      
            'message'=>"You don't have admin access rights",   
        ], 403);
       }
        return $next($request);
    }
}