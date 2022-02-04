<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    protected $fillable = [
        'name', 'start_date', 'start_date', 'end_date', 'details', 'status_id'
    ];
}
