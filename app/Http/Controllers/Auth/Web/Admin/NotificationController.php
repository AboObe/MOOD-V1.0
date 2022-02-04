<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = DB::table('notifications')
                        ->leftjoin('restaurants','restaurants.id','=','notifications.restaurant_id')
                        ->select('notifications.*','restaurants.name as restaurant_name')->get();
        $count = $notifications->count();

        return view('admin/notification/index',compact('notifications','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurants = DB::table('restaurants')->select('id','name')->get();
        return view('admin/notification/create',compact('restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $curl = curl_init();

        $devices_collection = DB::table('mobile_devices')->select('device_code')->get();
        $to= [];
        foreach ($devices_collection as $device) {
           array_push($to,$device->device_code);
        }       
        $title = $request->title;
        $body = $request->body;
        $restaurant_id = $request->restaurant_id;

        $notification_data = [];
        $image = "";
        if($restaurant_id != null)
        {
            //$my_restaurant = DB::table("restaurants")->where("restaurants.id",'=',$restaurant_id)->select('restaurants.image', 'restaurants.name')->first();
            $restaurant = DB::table('restaurants')
                ->leftjoin('statuses','restaurants.status_id','statuses.id')
                ->leftjoin('users','users.id','restaurants.manager_id')
                ->where('restaurants.id','=',$restaurant_id)
                ->select('restaurants.*','statuses.name as status_name','users.name as user_name')->first();
            
            $image = $restaurant->image;
            //$restaurant_name = $my_restaurant->name;

            $photos = json_decode($restaurant->photos);
        
            $tags = DB::table('tags')
            ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
            ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
            ->where('restaurants.id','=',$restaurant_id)
            ->select('tags.name')->get();

            $categories = DB::table('categories')
            ->leftjoin('restaurant_categories','categories.id','restaurant_categories.category_id')
            ->leftjoin('restaurants','restaurant_categories.restaurant_id','restaurants.id')
            ->where('restaurants.id','=',$restaurant_id)
            ->select('categories.name')->get();

            $campaigns = DB::table('campaigns')
            ->leftjoin('restaurant_campaigns','campaigns.id','restaurant_campaigns.campaign_id')
            ->leftjoin('restaurants','restaurant_campaigns.restaurant_id','restaurants.id')
            ->where('restaurants.id','=',$restaurant_id)
            ->where('campaigns.status_id','!=',1)
            ->select('campaigns.name')->get();
                        
            $notification_data[]=[
                "tags" => $tags,
                "result" => $restaurant,
                "photos" => $photos,
                "campaigns" => $campaigns,
                "categories" => $categories,
            ];  

        }
        

        $data = array("to" => $to, "title" => $title, "body" => $body);
        if($restaurant_id != null)
            $data["data"] = $notification_data[0];            

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Accept-Encoding: application/gzip",
            "Content-Type: application/json"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        Notification::create(
            ["title"=>$title,"body"=>$body,"restaurant_id"=>$restaurant_id,"image"=>$image]);
        
        return redirect()->intended('web_notification');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wep\Admin\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notifications = DB::table('notifications')
                        ->leftjoin('restaurants','restaurants.id','=','notifications.restaurant_id')
                        ->select('notifications.*','restaurants.name as restaurant_name')
                        ->where('notifications.id','=',$id)->get();
        $count = $notifications->count();

        return view('admin/notification/index',compact('notifications','count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wep\Admin\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wep\Admin\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wep\Admin\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
