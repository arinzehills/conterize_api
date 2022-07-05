<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentCreators extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_completed',
        'ongoing_projects',
        'niche',
        'role_type',
        'activated',
    ];
    
    public function user()
    {
       // return $this->belongsTo(User::class, 'user_id');
       return $this->belongsTo(User::class, 'user_id');
    }
}