<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use DB;

class NotificationController extends BaseController
{
	

    public function index()
    {
        $notifications = DB::table('notifications')
        ->leftjoin('restaurants','restaurants.id','=','notifications.restaurant_id')
        ->select('notifications.*','restaurants.name as restaurant_name')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return $this->sendResponse($notifications->toArray(), 'Restaurants retrieved successfully.');
    }


}
