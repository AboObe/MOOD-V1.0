<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'review','user_id','type','restaurant_id','service_id','offer_id'
    ];
}
