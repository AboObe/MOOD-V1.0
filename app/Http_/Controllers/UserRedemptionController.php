<?php

namespace App\Http\Controllers;


use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\UserRedemption;
use Validator;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Str;

class UserRedemptionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 
    /*
    *   Insert Redemption
    * 
    */
    public function insert_redeem(Request $request)
    {        
        $user_id = auth()->user()->id;
        $restaurant_id = $request->restaurant_id;
        $offer_id = $request->offer_id;
        $qr_ = $request->qr == null ? -1 : $request->qr;
        $pin_ = $request->pin == null ? -1 : $request->pin;

        $user_redemption;
        $old_redeem = DB::table('user_redemptions')
                    ->where('user_id','=',$user_id)
                    ->where('restaurant_id','=',$restaurant_id)
                    ->whereDate('created_at', '=', Carbon::today()->toDateString())
                    ->first();
        if($old_redeem != null){
            return $this->sendError("You can't create another redeem today!!");
        }
        else
        {
            
            $input = $request->all();
            $input["user_id"] = $user_id;
            

            $validator = Validator::make($input, [
                'restaurant_id' => 'required|numeric',
                'offer_id' => 'required|numeric',
                'pin' => 'required_without:qr',
                'qr' => 'required_without:pin',  
            ]);

            if($validator->fails()){
                return $validator->errors();       
            } 



            $res = DB::select('select id , name from restaurants where (id = '.$restaurant_id.' and (qr = "'.$qr_.'" or pin = "'.$pin_.'"))');
            
         
            if($res == null)
                return $this->sendError("error PIN or QR","",200);

            //create redeem code
            $last_user_redemption = UserRedemption::orderBy('id', 'desc')->first();
            $redeem_code = "";
            $year =  Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->year;
            $year_code = ($year == '2020' ? "A" :  ($year == '2021' ? "B" : "C" ));
            
            $month =  Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->month;
            $month_code =  ((int)$month) < 10 ? '0'.$month : $month;

            
            $code =  Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->month == Carbon::createFromFormat('Y-m-d H:i:s', $last_user_redemption->created_at)->month ? 
                ((int)substr($last_user_redemption->redeem_code, -5) + 1 ) :  '11100';


            $redeem_code = $year_code.'-'.$month_code.'-'.$code;
            $input["redeem_code"] = $redeem_code;


            $user_redemption = UserRedemption::create($input);
            

        }        

        return $this->sendResponse($user_redemption , 'User Redemption created successfully.');
    }
    /*
    *   Get All Redemption For Specific User
    *
    */
    public function get_redeem(){

        $user_id = auth()->user()->id;

        $user_redemptions = DB::table('user_redemptions')
                    ->leftjoin('offers','offers.id','user_redemptions.offer_id')
                    ->leftjoin('restaurants','restaurants.id','user_redemptions.restaurant_id')
                    ->where('user_id','=',$user_id)
                    ->select('user_redemptions.redeem_code as redeem_code','user_redemptions.id as redeem_id','restaurants.image as restaurant_image','restaurants.name as restaurant_name','offers.name as offer_name','user_redemptions.created_at')
                    ->get();

        return $this->sendResponse($user_redemptions , 'User Redemption created successfully.');
    }
}
