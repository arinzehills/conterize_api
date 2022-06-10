<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'price',
        'slug',
        'stripe_plan',
        'stripe_price_id',
    ];

    public function getRouteKeyName(){
        return 'slug';
    }
}