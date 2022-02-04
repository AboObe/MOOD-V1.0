<?php

namespace App\Http\Controllers;

use App\TagRestaurant;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator; 
use DB;
use App\Http\Resources\TagRestaurant as TagRestaurantResource;

class TagRestaurantController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'tag_id' => 'numeric',
            'restaurant_id' => 'numeric'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $tag_restaurant = TagRestaurant::create($input);

        return $this->sendResponse(new TagRestaurantResource($tag_restaurant), 'TagRestaurant created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TagRestaurant  $tagRestaurant
     * @return \Illuminate\Http\Response
     */
    public function show(TagRestaurant $tagRestaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TagRestaurant  $tagRestaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(TagRestaurant $tagRestaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TagRestaurant  $tagRestaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TagRestaurant $tagRestaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TagRestaurant  $tagRestaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag_restaurant = TagRestaurant::find($id);
        if ($tag_restaurant == null ) {
            return $this->sendError('tag_restaurant not found.');
        }
        $tag_restaurant->delete();
   
        return $this->sendResponse($tag_restaurant->toArray(), 'tag_restaurant deleted successfully.');
    }

    /***
     * All Tag for restaurant
     */
    public function get_tags_restaurant(Request $request){
        $tags = DB::table('tag_restaurants')
        ->leftjoin('tags','tag_restaurants.tag_id','tags.id')
        ->select('tags.*')->where('tag_restaurants.restaurant_id','=',$request->restaurant_id)->get();
        return $this->sendResponse($tags, 'Get All Tags successfully.');
    } 
     /**
      * All resturant for tag
      */
      public function get_restaurants_tag(Request $request){
        $restaurants = DB::table('tag_restaurants')
        ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
        ->select('restaurants.*')->where('tag_restaurants.tag_id','=',$request->tag_id)->get();
        return $this->sendResponse($restaurants, 'Get All Restaurants successfully.');

    
    }
}
