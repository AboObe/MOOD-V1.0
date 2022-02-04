<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = 'restaurants';

    protected $fillable = [
        'name','city','description','begin_opening_hours','opening_hours','email','phone'
        ,'address','latitude','longitude','image','status_id', 'facebook', 'instagram', 'snapchat'
		, 'whatsapp', 'youtube','website','manager_id','photos','qr', 'pin','is_featured'    ];
}