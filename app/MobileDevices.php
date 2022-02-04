<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileDevices extends Model
{
     protected $table = 'mobile_devices';
    
    protected $fillable = [
        'device_code', 'user_id' , 'status'
    ];
}
