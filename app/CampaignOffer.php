<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignOffer extends Model
{
    protected $table = 'campaign_offers';

    protected $fillable = [
        'offer_id', 'campaign_id', 'details'
    ];
}
