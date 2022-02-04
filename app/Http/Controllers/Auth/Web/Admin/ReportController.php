<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class ReportController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','Admin']);
    }


    public function APP_VIEWS(){

  		$views = DB::select(DB::raw("
  			select
  				year(created_at) as year,	month(created_at) as month, count(*) as count_v
			from
				log_api
			where
				event_name='app_open'
			group by
				year(created_at), month(created_at);
			"));
    	return view('admin/report/app_view',compact('views'));
    }


    public function RESTAURANT_VIEWS(){

  		$views = DB::select(DB::raw("
  			select restaurants.name as name,count(*) as count_r from log_api join restaurants on log_api.event_type = restaurants.id  where event_name='restaurant_open' group by event_type,restaurants.name
			"));
    	return view('admin/report/restaurant_view',compact('views'));
    }

    public function RESTAURANT_VIEWS_WITH_SEARCH(Request $request)
    {
    	$year = ($request->year == "" ? "1=1": "year(log_api.created_at)=".$request->year);
  		$month = ($request->month == "" ? "1=1": "month(log_api.created_at)=".$request->month);

  		$views = DB::select(DB::raw("
  			select restaurants.name as name,count(*) as count_r from log_api join restaurants on log_api.event_type = restaurants.id  where event_name='restaurant_open' and ".$year." and ".$month." group by event_type,restaurants.name
			"));

  		return json_encode($views);
    }

    public function REDEMPTIONS(){
        if(Auth::user()->type == 'admin')
            $redemptions = DB::table('user_redemptions')
                        ->leftjoin('users','users.id','user_redemptions.user_id')
                        ->leftjoin('restaurants','restaurants.id','user_redemptions.restaurant_id')
                        ->leftjoin('offers','offers.id','user_redemptions.offer_id')
                        ->select('users.name as user_name','restaurants.name as restaurant_name','offers.name as offer_name','user_redemptions.created_at as created_at')
                        ->get();
        if(Auth::user()->type == 'restaurant_manager')
            $redemptions = DB::table('user_redemptions')
                    ->leftjoin('users','users.id','user_redemptions.user_id')
                    ->leftjoin('restaurants','restaurants.id','user_redemptions.restaurant_id')
                    ->leftjoin('offers','offers.id','user_redemptions.offer_id')
                    ->select('users.name as user_name','restaurants.name as restaurant_name','offers.name as offer_name','user_redemptions.created_at as created_at')
                    ->where('restaurants.manager_id','=',Auth::user()->id)
                    ->get();
        return view('admin/report/redemptions',compact('redemptions'));

    }
}
