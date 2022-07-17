<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Carbon\Carbon;
use Cache;

use JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\CustomResetNotification;
use Laravel\Cashier\Billable;
use Mpociot\Teamwork\Traits\UserHasTeams;

class User extends Authenticatable implements JWTSubject,CanResetPassword
{
    use HasApiTokens,
     HasFactory, 
     Notifiable,Billable,UserHasTeams;
    
    //  protected $connection='mongodb';
    //  protected $collection='users';
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
        'payment_status',
        'plan',
        'nationality',
        'last_seen',
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
    protected $appends = ['online_status'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d/m/Y',
        // 'last_seen' => Carbon\Carbon::parse(''),
    ];
    public function getLastSeenAttribute($value){
        return Carbon::parse($value)->diffForHumans();//this converts lastseen to like 10sec ago
    }
    // public function getUserTypeAttribute($value){
        
    //     return $value;//this converts lastseen to like 10sec ago
    // }
    protected function OnlineStatus (): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
        );
    }
    public function getOnlineStatusAttribute($value){
        $last_seen=$this->getLastSeenAttribute($value);
        if (Cache::has('user-is-online-'.$this->getKey())){
            // Carbon::parse($last_seen)->diffForHumans();
        return 'Online';
    }
        else{
        // Carbon::parse($last_seen)->diffForHumans();
        return 'Offline';}//this converts lastseen to like 10sec ago
    }
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
        public function admin()
        {
            return $this->hasMany(Admin::class);
        }
        public function content_creators()
        {
            return $this->hasOne(ContentCreators::class);
        }
}