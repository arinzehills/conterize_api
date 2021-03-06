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
        'size',
        'reference_links',
        'description',
        'writing_topics',
        'supporting_info',
        'supporting_materials',
        'uploaded_file_urls',
        'video_format',
        'overview',
    ];
    protected $casts = [
        'supporting_materials'=>'array',
        'uploaded_file_urls'=>'array',
        'reference_links' => 'array',
        'writing_topics'=>'array'
    ];

    public function request()
    {
       // return $this->belongsTo(User::class, 'user_id');
       return $this->belongsTo(Request::class, 'user_id');
    }
}