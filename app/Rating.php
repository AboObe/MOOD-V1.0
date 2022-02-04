<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'rating', 'user_id','type','restaurant_id','service_id','offer_id'
    ];
}