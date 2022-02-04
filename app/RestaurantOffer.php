<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantOffer extends Model
{
     protected $table = 'restaurant_offers';
    
    protected $fillable = [
        'restaurant_id', 'offer_id'
    ];
}
