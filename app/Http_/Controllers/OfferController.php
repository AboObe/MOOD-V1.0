<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Offer;
use Validator;
use DB;
use App\Http\Resources\Offers as OfferResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class OfferController extends BaseController
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
        $offers = DB::table('offers')->paginate(50);
    
        return $this->sendResponse($offers->toArray(), 'Offers retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        $statuses = DB::select('select id , name from statuses');
        $restaurants = DB::select('select id , name from restaurants where status_id <> 1');
        $data = [
            'statuses' => $statuses,
            'restaurants' => $restaurants
        ];
        return $this->sendResponse($data, 'Statuses and Restaurants retrieved successfully.'); 
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
            'name' => 'required',
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
            
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Offer'.'/'.$fileName;
        }

        $input['image'] = $fileName;
        $offer = Offer::create($input);

        return $this->sendResponse(new OfferResource($offer), 'Offer created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $offer = DB::table('offers')
        ->leftjoin('statuses','statuses.id','offers.status_id')
        ->select('offers.*','statuses.name as status_name')
        ->where('offers.id','=',$id)->get();
        if (is_null($offer)) {
            return $this->sendError('Offer not found.');
        }
        
        return $this->sendResponse($offer, 'Offer retrieved successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::find($id);
        if ($offer == null ) {
            return $this->sendError('Offer not found.');
        }
        $statuses = DB::select('select id, name from statuses');
        $restaurants = DB::select('select id , name from restaurants where status_id <> 1');
        $data = [
            'statuses' => $statuses,
            'restaurants' => $restaurants,
            'offer' => $offer
        ];
        return $this->sendResponse($data, 'Restaurant and statuses retrieved successfully.');
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $offer = Offer::find($id);
        if ($offer == null ) {
            return $this->sendError('Offer not found.');
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            //delete old image
            Storage::delete($offer->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Offer'.'/'.$fileName;
        }
        $input['image'] = $fileName;
   
        Offer::where('id', $id)->update($input);
   
        return $this->sendResponse(new OfferResource($offer), 'Offer updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $offer = Offer::find($id);
        if ($offer == null ) {
            return $this->sendError('Offer not found.');
        }
        $input['status_id'] = 1; 
        Offer::where('id', $id)->update($input);
        /*
        Storage::delete($offer->image);
        $offer->delete();
        */
        return $this->sendResponse([], 'Offer deleted successfully.');
    }

    /**
     * Get All Services belong to Offer and offer details
     */
    public function offer_services(Request $request){
        $offer = DB::table('offers')
        ->leftJoin('restaurants','restaurants.id','=','offers.restaurant_id')
        ->leftJoin('statuses','statuses.id','=','offers.status_id')
        ->select('restaurants.name as restaurant_name','restaurants.phone as restaurant_phone',
        'restaurants.city as restaurant_city','offers.price as offers_price','offers.image as offer_image'
        ,'offers.details as offer_details','offers.start_time as offer_start_time','offers.end_time as offer_end_time'
        ,'offers.start_date as offer_start_date','offers.end_date as offer_end_date','offers.name as offer_name'
        ,'statuses.name as status_offer')->where('offers.id','=',$request->offer_id)->get();
        
        
        $services = DB::table('offers')
        ->leftJoin('services_offers','offers.id','=','services_offers.offer_id')
        ->leftJoin('services','services.id','=','services_offers.service_id')
        ->leftJoin('statuses','statuses.id','=','services.status_id')
        ->select('services_offers.details as servic_offer_details','services.*')
        ->where('offers.id','=',$request->offer_id)->get();

        $data=[
            'offer' => $offer,
            'services' => $services
        ];
        return $this->sendResponse($data, 'All Services belong to offer successfully.');
    }
    

}
