<?php

namespace App\Http\Controllers;


use App\Http\Controllers\BaseController as BaseController;
use App\CampaignOffer;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Http\Resources\CampaignOffer as CampaignOfferResource;
class CampaignOfferController extends BaseController
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
            'offer_id' => 'required | numeric',
            'campaign_id' => 'required | numeric'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $campaign_offer = CampaignOffer::create($input);

        return $this->sendResponse(new CampaignOfferResource($campaign_offer), 'CampaignOffer created successfully.');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CampaignOffer  $campaignOffer
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignOffer $campaignOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CampaignOffer  $campaignOffer
     * @return \Illuminate\Http\Response
     */
    public function edit(CampaignOffer $campaignOffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CampaignOffer  $campaignOffer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampaignOffer $campaignOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CampaignOffer  $campaignOffer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign_offer = CampaignOffer::find($id);
        if ($campaign_offer == null ) {
            return $this->sendError('CampaignOffer not found.');
        }
        $campaign_offer->delete();
   
        return $this->sendResponse($campaign_offer->toArray(), 'CampaignOffer deleted successfully.');
    }

    /**
     * Get All offers in campaign
     */
    public function get_offers(Request $request)
    {
        $offers = DB::table('offers')
        ->leftjoin('campaign_offers','campaign_offers.offer_id','offers.id')
        ->select('campaign_offers.details as campaign_offer_details'
        ,'offers.*')->where('campaign_offers.campaign_id','=',$request->campaign_id)->get();

        return $this->sendResponse($offers, 'GET offers related with specific Campaign successfully.');
    }

    /**
     * Get All campaigns of offer
     */
    public function get_campaigns(Request $request)
    {
        $campaigns = DB::table('campaigns')
        ->leftjoin('campaign_offers','campaign_offers.campaign_id','campaigns.id')
        ->select('campaign_offers.details as campaign_offer_details'
        ,'campaigns.*')->where('campaign_offers.offer_id','=',$request->offer_id)->get();

        return $this->sendResponse($campaigns, 'GET campaigns related with specific Offer successfully.');
    }
}
