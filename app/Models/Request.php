<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'request_name',
        'category',
        'assign_to',
        'assign_to_id',
        'submitted_by',
        'status',
        'is_draft',
        
    ];

    public function user()
    {
       // return $this->belongsTo(User::class, 'user_id');
       return $this->belongsTo(User::class, 'user_id');
    }
    public function requestDetail()
        {
            //return $this->hasMany(Order::class);
            return $this->hasOne(RequestDetail::class);
        }
        public function deliveries()
        {
            return $this->hasMany(Deliveries::class);
        }
}