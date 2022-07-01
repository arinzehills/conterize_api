<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliveries extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_id',
        'senders_id',
        'comments'
    ];
    public function requests()
    {
        // return $this->hasMany(Request::class);
       return $this->belongsTo(Request::class, 'request_id');

    }
}