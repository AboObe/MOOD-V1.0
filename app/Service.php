<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    
    protected $fillable = [
        'name','price','image','content','status_id','restaurant_id','category_id'
    ];
}