<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = [
        'restaurant_id','price','image','details','start_time','end_time'
        ,'start_date','end_date','status_id','name'
    ];
}