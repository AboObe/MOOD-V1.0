<?php

namespace App\Http\Controllers;


use App\Http\Controllers\BaseController as BaseController;
use App\Campaign;
use Illuminate\Http\Request; 
use Validator; 
use DB;
use App\Http\Resources\Campaign as CampaignResource;

class CampaignController extends BaseController
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
        $campaigns = Campaign::all();
    
        return $this->sendResponse($campaigns->toArray(), 'campaigns retrieved successfully.');
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $offers = DB::table('offers')->get(); 
        $statuses = DB::select('select id , name from statuses');
        $data = [
            'offers' => $offers,
            'statuses' => $statuses
        ];
        return $this->sendResponse($data, 'Statuses and offers retrieved successfully.'); 
    
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
            'name' => 'required | unique:campaigns',
            'status_id' => 'numeric',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
      
        $campaign = Campaign::create($input);

        return $this->sendResponse(new CampaignResource($campaign), 'Offer created successfully.');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $campaign = DB::table('campaigns')
        ->leftjoin('statuses','statuses.id','campaigns.status_id')
        ->leftjoin('campaign_offers','campaign_offers.campaign_id','campaigns.id')
        ->leftjoin('offers','offers.id','campaign_offers.offers')
        ->select('campaigns.*','statuses.name as status_name','campaign_offers.details as campaign_offer_details'
        ,'offers.name as offer_name','offers.price as offer_price')
        ->where('campaigns.id','=',$id)->get();
  
        if (is_null($campaign)) {
            return $this->sendError('Campaign not found.');
        }

        return $this->sendResponse($campaign, 'Campaign retrieved successfully.');
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        if ($cmpaign == null ) {
            return $this->sendError('Campaign not found.');
        }
        $offers = DB::table('offers')->get(); 
        $statuses = DB::select('select id , name from statuses');
        $data = [
            'offers' => $offers,
            'statuses' => $statuses,
            'campaign' => $campaign,
        ];
        return $this->sendResponse($data, 'campaign , offers and statuses retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return $this->sendError('Campaign not found.');
        }
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required | unique:campaigns,name,'.$id,
            'status_id' => 'numeric',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return $this->sendError('Campaign not found.');
        }
        
        Campaign::where('id', $id)->update($input);
        
        $campaign->save();

        $campaign = Campaign::find($id);
   
        return $this->sendResponse(new CampaignResource($campaign), 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign == null ) {
            return $this->sendError('Campaign not found.');
        }
        $input['status_id'] = 1; 
        Campaign::where('id', $id)->update($input);
        /*
        Storage::delete($campaign->image);
        $campaign->delete();
        */
        return $this->sendResponse([], 'Campaign deleted successfully.');
    }
}
