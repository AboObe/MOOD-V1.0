<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogAPI extends Model
{
    protected $table = 'log_api';

    protected $fillable = [
        'event_name', 'user_id', 'event_type', 'event_details', 'description'
    ];
}
