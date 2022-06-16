<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id',
        'request_name',
        'request_type',
        'category',
        'quantity',
        'reference_links',
        'description',
        'writing_topics',
        'supporting_info',
        'supporting_materials',
        'video_format',
        'overview',
    ];
    protected $casts = [
        'supporting_materials'=>'array',
        'reference_links' => 'array',
        'writing_topics'=>'array'
    ];

    public function request()
    {
       // return $this->belongsTo(User::class, 'user_id');
       return $this->belongsTo(Request::class, 'user_id');
    }
}