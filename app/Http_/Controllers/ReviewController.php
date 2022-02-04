<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Review;
use Validator;
use App\Http\Resources\Review as ReviewResource;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use App\Restaurant;

class ReviewController extends BaseController
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
    public function review_restaurant(Request $request){
            
            $input = $request->all();
            $user_id = auth()->user()->id;

            $validator = Validator::make($input, [
                'review' => 'required',
                'restaurant_id' => 'required'
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            $user_review = DB::table('reviews')->updateOrInsert(['restaurant_id' => $input['restaurant_id'],'user_id' => $user_id],["review" => $input['review']]);

            
            return $this->sendResponse($user_review , 'Review created successfully.');
    }


    public function get_reviews_for_restaurant(Request $request){
        $restaurant_id = $request->restaurant_id;

        $reviews = DB::table('reviews')
                    ->leftjoin('users','users.id','reviews.user_id')
                    ->where('restaurant_id','=',$restaurant_id)
                    ->select('reviews.id','reviews.user_id','reviews.restaurant_id','reviews.review','users.name as user_name')
                    ->get();

        return $this->sendResponse($reviews , 'reviews of restaurant');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $review = Review::find($id);
        if ($review == null ) {
            return $this->sendError('Review not found.');
        }

        $review->delete();
        return $this->sendResponse([], 'Review deleted successfully.');
    }
    
}
