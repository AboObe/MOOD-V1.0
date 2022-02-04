<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicesOffer extends Model
{
    protected $table = 'services_offers';
    
    protected $fillable = [
        'offer_id','service_id','details'
    ];
}
