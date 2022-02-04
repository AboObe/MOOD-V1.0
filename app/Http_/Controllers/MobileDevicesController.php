<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\MobileDevices;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class MobileDevicesController extends BaseController
{
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
        
        $device_code = $request['device_code'];
        $user_id = $request['user_id'];

        $mobile = DB::table('mobile_devices')->where('device_code','=',$device_code)->first();
        if($mobile == null){
            $mobile_device = MobileDevices::create(["device_code"=>$device_code]);
            return $this->sendResponse( $mobile_device , 'Mobile Device  Saved successfully.');
        }
        else{
            if($user_id != null ){
                MobileDevices::where('id', $mobile->id)->update(["user_id"=>$user_id]);
                $mobile = DB::table('mobile_devices')->where('device_code','=',$device_code)->first();
                return $this->sendResponse( $mobile , 'Mobile Device  Updated successfully.');
            }
        }
        return $this->sendResponse( $mobile , 'Mobile Device  exist allready .');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MobileDevices  $mobileDevices
     * @return \Illuminate\Http\Response
     */
    public function show(MobileDevices $mobileDevices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileDevices  $mobileDevices
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileDevices $mobileDevices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileDevices  $mobileDevices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobileDevices $mobileDevices)
    {
        $mobile_devices = DB::table('mobile_devices')->where('user_id','=',$request['user_id'])->get();
        foreach ($mobile_devices as $mobile_device) {
            MobileDevices::where('id', $mobile_device->id)->update(["status"=>$request['status']]);
        }
        return $this->sendResponse('The Status was Changed Successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileDevices  $mobileDevices
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileDevices $mobileDevices)
    {
        //
    }
}
