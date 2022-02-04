<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantCampaign extends Model
{
     protected $table = 'restaurant_campaigns';
    
    protected $fillable = [
        'restaurant_id', 'campaign_id'
    ];
}
