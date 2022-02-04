<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Campaign;
use App\RestaurantCampaign;
use Validator;
use DB;

class CampaignController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','Admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = DB::table('campaigns')
        ->leftjoin('statuses','statuses.id','campaigns.status_id')
        ->select('campaigns.*','statuses.name as status_name')->get();

        $count = $campaigns->count();

        return view('admin/campaign/index',compact('campaigns','count')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::table('statuses')->select('id','name')->get();
        $restaurants = DB::table('restaurants')->select('id','name')->where('status_id','!=',1)->get();
        return view('admin/campaign/create',compact('statuses','restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();  
        $validator = Validator::make($input, [
            'name' => 'required|unique:campaigns',
            'status_id' => 'numeric'
        ]);
   
        if($validator->fails()){
            return $validator->errors();       
        }        
        //create new campaign
        $campaign = Campaign::create($input);
        //get unique restaurants to related it with campaign
        $collection = collect($request->new_restaurants);
        $restaurants = $collection->unique();
        //related restaurant with campaign
        foreach($restaurants as $restaurant)
        {
            RestaurantCampaign::create(['restaurant_id'=>$restaurant,'campaign_id'=>$campaign->id]);
        }

        return redirect()->intended('web_campaign');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = DB::table('campaigns')
        ->leftjoin('statuses','statuses.id','campaigns.status_id')
        ->where('campaigns.id','=',$id)
        ->select('campaigns.*','statuses.name as status_name')->first();
        if (is_null($campaign)) {
            return 'Campaign not found.';
        }
        
        $restaurants = DB::table('restaurants')
        ->leftjoin('restaurant_campaigns','restaurant_campaigns.restaurant_id','restaurants.id')
        ->where('restaurant_campaigns.campaign_id','=',$id)
        ->select('restaurants.name')->get();

        return view('admin/campaign/show',compact('campaign','restaurants'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return 'Campaign not found.';
        }
       

       $campaign_restaurants = DB::table('restaurants')
        ->leftjoin('restaurant_campaigns','restaurant_campaigns.restaurant_id','restaurants.id')
        ->where('restaurant_campaigns.campaign_id','=',$id)
        ->select('restaurants.id','restaurants.name')->get();

        $statuses = DB::table('statuses')->select('id','name')->get();
        $restaurants = DB::table('restaurants')->select('id','name')->where('status_id','!=',1)->get();

        return view('admin/campaign/edit',compact('statuses','campaign','restaurants','campaign_restaurants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return 'Campaign not found.';
        }
        
        $input = [
            'name' => $request->name,
            'details' => $request->details,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status_id' => $request->status_id,
        ];
        $validator = Validator::make($input, [
            'name' => 'required | unique:campaigns,name,'.$id,
            'status_id' => 'numeric',
        ]);
        
        if($validator->fails()){
            return $validator->errors();       
        }


        Campaign::where('id', $id)->update($input);
        
        $campaign->save();

        //restaurant campaign
        /*
        * Update Restaurants_campaign
        */
        $new_restaurants_without_null = array_filter($request->new_restaurants);

        $create_restaurants = 
          ( $request->old_restaurants == null ? 
              $new_restaurants_without_null : 
                  ($new_restaurants_without_null == null ?
                      null : array_diff($new_restaurants_without_null,$request->old_restaurants)));

        $delete_restaurants = 
          ( $new_restaurants_without_null == null ?
              $request->old_restaurants : 
                  ($request->old_restaurants == null ? 
                    null : array_diff($request->old_restaurants,$new_restaurants_without_null)));
        
        if($delete_restaurants != null){
          foreach($delete_restaurants as $del)
          {
          $restaurant_id = (int)$del;
          RestaurantCampaign::where('restaurant_id', $restaurant_id)->where('campaign_id',$id)->delete();
          }
        }
        if($create_restaurants != null){
          foreach($create_restaurants as $cre) {
            $restaurant_id = (int)$cre;
            RestaurantCampaign::create(["restaurant_id"=>$restaurant_id,"campaign_id"=>$id]);
          }
        }
        //end

         
        return redirect()->intended('web_campaign');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return 'Campaign not found.';
        }
        $input['status_id'] = 1; 
        Campaign::where('id', $id)->update($input);

        return redirect()->intended('web_campaign');
    }
}
