<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Rating;
use Validator;
use App\Http\Resources\Ratings as RatingResource;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Restaurant;

class RatingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a newly created or updated resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function rating_restaurant(Request $request){
            
            $input = $request->all();
            $user_id = auth()->user()->id;

            $validator = Validator::make($input, [
                'rating' => 'required',
                'restaurant_id' => 'required'
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $user_rating = DB::table('ratings')->updateOrInsert(['restaurant_id' => $input['restaurant_id'],'user_id' => $user_id,"type" => "restaurant"],["rating" => $input['rating']]);

            
            //update rating restaurant
            $sum_rating = 0;
            $ratings = DB::table('ratings')->where('restaurant_id','=',$input['restaurant_id'])->get();
            foreach($ratings as $rating)
                $sum_rating = $sum_rating + $rating->rating;
            $count = $ratings->count();
            

            $new_rating = $sum_rating/ $count ;

            Restaurant::where('id', $input['restaurant_id'])->update(['rating'=> $new_rating]);
        

            return $this->sendResponse($new_rating , 'Rating created successfully.');
    }


    public function get_rating_for_user(Request $request){
        $user_id = auth()->user()->id;
        $restaurant_id = $request->restaurant_id;

        $user_rating = DB::table('ratings')
                    ->where('restaurant_id','=',$restaurant_id)
                    ->where('user_id','=',$user_id)
                    ->select('id','user_id','restaurant_id','rating')
                    ->first();

        return $this->sendResponse($user_rating , 'Rating for restaurant');


    }
    

}
