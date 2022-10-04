<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCredits extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_purchased_credits',
        'total_used_credits',
        'content_writing_credits',
        'graphics_credits',
        'video_credits',
    ];
    protected $casts = [
        'total_purchased_credits' => 'array',
        'total_used_credits' => 'array',
        'content_writing_credits' => 'array',
        'graphics_credits' => 'array',
        'video_credits' => 'array',
    ];
    
    protected $attributes = [
        'total_purchased_credits' => '{
            "content_writing": "0",
            "graphics": "0",
            "video": "0"
        }',
        'total_used_credits' => '{
            "content_writing": "0",
            "graphics": "0",
            "video": "0"
        }',
        'graphics_credits' => '{
            "total_credits": "0",
            "used_credits": "0",
            "leftover_credits": "0"
        }',
        'content_writing_credits' => '{
            "total_credits": "0",
            "used_credits": "0",
            "leftover_credits": "0"
        }',
        'video_credits' => '{
            "total_credits": "0",
            "used_credits": "0",
            "leftover_credits": "0"
        }'
    ];
    public function user()
    {
       return $this->belongsTo(User::class);
    }
}