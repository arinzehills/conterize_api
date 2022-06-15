<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;

use JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\CustomResetNotification;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements JWTSubject,CanResetPassword 
{
    use HasApiTokens, HasFactory, Notifiable,Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'user_type',
        'role_type',
        'nationality'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d/m/Y',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function checkToken($token){
        if($token->token){
            return true;
        }
        return false;
    }
    public static function getCurrentUser($request){
        if(!User::checkToken($request)){
            return response()->json([
             'message' => 'Token is required'
            ],422);
        }
         
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
     }
     public function company()
        {
            return $this->hasMany(Company::class);
            // return $this->hasOne(Company::class);
        }
        public function request()
        {
            return $this->hasMany(Request::class);
        }
}