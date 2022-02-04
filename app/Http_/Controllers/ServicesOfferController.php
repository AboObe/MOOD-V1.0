<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\ServicesOffer;
use Validator;
use DB;
use App\Http\Resources\ServicesOffer as ServicesOfferResource;
use Illuminate\Support\Facades\Input;

class ServicesOfferController extends BaseController
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
      
        $services_offer = ServicesOffer::all();
        return $this->sendResponse($services_offer->toArray(), 'ServicesOffer retrieved successfully.');
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        $services = DB::select('select id , name from services where status_id <> 1');
        return $this->sendResponse($services, 'Services retrieved successfully.'); 
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
            'offer_id' => 'required',
            'service_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $service_offer = ServicesOffer::create($input);

        return $this->sendResponse(new ServicesOfferResource($service_offer), 'ServicesOffer created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $service_offer = ServicesOffer::find($id);
        if (is_null($service_offer)) {
            return $this->sendError('ServicesOffer not found.');
        }
        
        return $this->sendResponse(new ServicesOfferResource($service_offer), 'ServicesOffer retrieved successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service_offer = ServicesOffer::find($id);
        if ($service_offer == null ) {
            return $this->sendError('ServicesOffer not found.');
        }
    
        $services = DB::select('select id , name from services where status_id <> 1');
        $data=[
            'services'=> $services,
            'service_offer' => $service_offer
        ];
        return $this->sendResponse($data, 'service_offer and services retrieved successfully.');
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
            'offer_id' => 'required',
            'service_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $service_offer = ServicesOffer::find($id);
        if ($service_offer == null ) {
            return $this->sendError('ServicesOffer not found.');
        }
        
        ServicesOffer::where('id', $id)->update($input);
   
        return $this->sendResponse(new ServicesOfferResource($service_offer), 'ServicesOffer updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $service_offer = ServicesOffer::find($id);
        if ($service_offer == null ) {
            return $this->sendError('ServicesOffer not found.');
        }
        $service_offer->delete();
        
        return $this->sendResponse([], 'ServicesOffer deleted successfully.');
    }
}
