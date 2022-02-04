<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\LogAPI;
use Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;


class LogAPIController extends BaseController
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
    public function store_log(Request $request){
            
            $input = $request->all();
            $user_id = auth()->user()->id;

            $log_api = LogAPI::create($input);

            
            return $this->sendResponse($log_api , 'log_api created successfully.');
    }
}
