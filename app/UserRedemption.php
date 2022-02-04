<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRedemption extends Model
{
    protected $table = 'user_redemptions';
    
    protected $fillable = [
        'restaurant_id', 'user_id' , 'offer_id', 'redeem_code'
    ];
}
