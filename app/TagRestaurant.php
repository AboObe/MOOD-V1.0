<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagRestaurant extends Model
{
    protected $table = 'tag_restaurants';
    
    protected $fillable = [
        'restaurant_id', 'tag_id'
    ];
}
