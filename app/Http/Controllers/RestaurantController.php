<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Restaurant;
use Validator;
use DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Restaurants as RestaurantResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RestaurantController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show','get_restaurants', 'restaurant_offers','get_featured_resturants');
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurants = DB::table('restaurants')->paginate(50);
        return $this->sendResponse($restaurants->toArray(), 'Restaurants retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        return "no action";
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
         return "no action";
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
		$restaurant = DB::table('restaurants')
        ->leftjoin('statuses','restaurants.status_id','statuses.id')
        ->leftjoin('users','users.id','restaurants.manager_id')
        ->where('restaurants.id','=',$id)
        ->select('restaurants.*','statuses.name as status_name','users.name as user_name')->first();
      

        if (is_null($restaurant)) {
            return $this->sendError('Restaurant not found.');
        }

        $photos = json_decode($restaurant->photos);
        
        
        $tags = DB::table('tags')
        ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
        ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('tags.name')->get();

        $categories = DB::table('categories')
        ->leftjoin('restaurant_categories','categories.id','restaurant_categories.category_id')
        ->leftjoin('restaurants','restaurant_categories.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('categories.name')->get();


        $campaigns = DB::table('campaigns')
        ->leftjoin('restaurant_campaigns','campaigns.id','restaurant_campaigns.campaign_id')
        ->leftjoin('restaurants','restaurant_campaigns.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->where('campaigns.status_id','!=',1)
        ->select('campaigns.name')->get();
        
        
        $data[]=[
            "tags" => $tags,
            "result" => $restaurant,
            "photos" => $photos,
            "campaigns" => $campaigns,
            "categories" => $categories,
        ];        

        return $this->sendResponse($data , 'Restaurant retrieved successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       return "no action";
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
       return "no action";
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);
        if ($restaurant == null ) {
            return $this->sendError('Restaurant not found.');
        }
        $input['status_id'] = 1; 
        Restaurant::where('id', $id)->update($input);
        /*
        Storage::delete($restaurant->image);
        $restaurant->delete();
        */
        return $this->sendResponse([], 'Restaurant deleted successfully.');
    }

    /**
     * Get All Offers belong to restaurent
     */
    public function restaurant_offers(Request $request){
        $offers = DB::table('restaurants')
        ->leftJoin('offers','restaurants.id','=','offers.restaurant_id')
        ->leftJoin('statuses','statuses.id','=','offers.status_id')
        ->select('restaurants.name as restaurant_name','restaurants.phone as restaurant_phone',
        'restaurants.city as restaurant_city','offers.*','statuses.name as status_name')
        ->where('restaurants.id','=',$request->restaurant_id)
        ->where('offers.status_id','=','2')->get();
        return $this->sendResponse($offers, 'All Offers belong to restaurent successfully.');
    }
    /**
     * Get All Services belong to restaurent
     */
    public function restaurant_services(Request $request){
        $services = DB::table('restaurants')
        ->leftJoin('services','restaurants.id','=','services.restaurant_id')
        ->leftJoin('statuses','statuses.id','=','services.status_id')
        ->leftJoin('categories','categories.id','=','services.category_id')
        ->select('restaurants.name as restaurant_name','restaurants.phone as restaurant_phone',
        'restaurants.city as restaurant_city','services.*','statuses.name as status_name'
        ,'categories.name as category_name')
        ->where('restaurants.id','=',$request->restaurant_id)->get();
        return $this->sendResponse($services, 'All Services belong to restaurent successfully.');
    }


    /***
     * Resturant after filter
     */
    public function get_restaurants(Request $request)
    {	
   
		$lati = $request->latitude;
        $long = $request->longitude;
		
        $query = DB::table('restaurants')
					->where('restaurants.status_id','!=', 1)
					->select('restaurants.*');
	
		
		if($request->name != null)
            $query->where('restaurants.name','like','%'.$request->name.'%');
		
		if($request->address != null)
            $query->where('restaurants.address','like','%'.$request->address.'%');
        
		if($request->tag != null){
			$query->leftjoin('tag_restaurants','tag_restaurants.restaurant_id','restaurants.id')
					->leftjoin('tags','tags.id','tag_restaurants.tag_id')
					->where('tags.id','=',$request->tag)
					->select('restaurants.*', 'tags.name as tag_name');
		}
		
		if($request->category != null){
            $query->leftjoin('restaurant_categories','restaurant_categories.restaurant_id','restaurants.id')
					->leftjoin('categories','categories.id','restaurant_categories.category_id')
					->where('categories.status_id','!=','1')
					->where('categories.id','=',$request->category);
		}
		if($request->campaign != null){
			$query->leftjoin('restaurant_campaigns','restaurant_campaigns.restaurant_id','restaurants.id')
					->leftjoin('campaigns','campaigns.id','restaurant_campaigns.campaign_id')
					->where('campaigns.status_id','!=','1')
                    ->where('campaigns.id','=',$request->campaign);
		}
		if($request->price != null){
			$query->leftjoin('offers','offers.restaurant_id','restaurants.id')
					->where('offers.status_id','!=','1')
					->where('offers.price','<',$request->price);
		}

		
		if($lati != null && $long != null){
			/*$query->selectRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati])
				->whereRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) < 50',[$lati,$long,$lati])
				->orderByRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati]);*/
            $query->selectRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati])
                ->orderByRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati]);
		}

        if($request->popularity != null)
            $query->sortBy('restaurants.rating');
        

		$result_restaurants = $query->get();
        
        $data = [];
		
        foreach ($result_restaurants as $rest) {
            $photos = json_decode($rest->photos);
            $tags = DB::table('tags')
                ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
                ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
                ->where('restaurants.id','=',$rest->id)
                ->select('tags.name')->get();
            $data[]=[
                    "tags" => $tags,
                    "result" => $rest,
                    "photos" => $photos
            ]; 			
        }
				
		$perPage = 50;
        $current_page = LengthAwarePaginator::resolveCurrentPage();

        $restaurants_collection = new Collection($data); // needs a use statement

        $current_page_restaurants = $restaurants_collection->slice(($current_page - 1) * $perPage, $perPage)->all(); // slice($offset, $number_of_item)

        $current_page_restaurants = array_slice($data, ($current_page - 1) * $perPage, $perPage);

        $restaurants_to_show = new LengthAwarePaginator($current_page_restaurants, count($restaurants_collection), $perPage);

        return $this->sendResponse($restaurants_to_show , 'Restaurants  and tags retrieved successfully.'); 
            
    }
	
	public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    /*
    *   get featured resturants
    */
    public function get_featured_resturants(Request $request)
    {
        $lati = $request->latitude;
        $long = $request->longitude;
        
        $query = DB::table('restaurants')
                    ->where('restaurants.status_id','!=', 1)
                    ->where('restaurants.is_featured','=',1)
                    ->select('restaurants.*');

        if($lati != null && $long != null){
            /*$query->selectRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati])
                ->whereRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) < 50',[$lati,$long,$lati])
                ->orderByRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati]);*/
            $query->selectRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati])
                ->orderByRaw('(6371*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude))))',[$lati,$long,$lati]);
        }
        else
            $query->orderBy('name');
        
        $result_restaurants = $query->get();
        
        $data = [];
        $i = 0;

        foreach($result_restaurants as $rest) {
            if($i < $request->count){
                $photos = json_decode($rest->photos);
                $tags = DB::table('tags')
                    ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
                    ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
                    ->where('restaurants.id','=',$rest->id)
                    ->select('tags.name')->get();
                $data[]=[
                        "tags" => $tags,
                        "result" => $rest,
                        "photos" => $photos
                ];
                $i++;
            }
            else
                break;     
        }

        return $this->sendResponse($data, 'Return Featured restaurent successfully.');
    }


}
