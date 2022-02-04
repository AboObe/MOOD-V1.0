<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Offer; 
use Validator;
use DB;
use Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class MyOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = DB::table('offers')
        ->leftjoin('restaurants','restaurants.id','offers.restaurant_id')
        ->select('offers.*','restaurants.name as restaurant_name')
        ->where('restaurants.manager_id','=',auth()->user()->id)
        ->get();
        $count = $offers->count();

        return view('restaurant_manager/offer/index',compact('offers','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::select('select id , name from statuses');
        $restaurants = DB::table('restaurants')->where('status_id', '<>', 1)
        ->where('manager_id','=',auth()->user()->id)->select('id','name')->get();

        return view('restaurant_manager/offer/create',compact('statuses','restaurants'));
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
        ]);
   
        if($validator->fails()){
            return  $validator->errors();       
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            $validator = Validator::make($input, [
                'image' => 'file|image|max:5000',   
            ]);
       
            if($validator->fails()){
                return $validator->errors();       
            }
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Offer'.'/'.$fileName;
            $input['image'] = $fileName;
        }

        
        $offer = Offer::create($input);

        return redirect()->intended('web_my_offer');
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
        ->leftjoin('restaurants','restaurants.id','offers.restaurant_id')
        ->select('offers.*','restaurants.name as restaurant_name','statuses.name as status_name')
        ->where('offers.id','=',$id)->first();
        if (is_null($offer)) {
            return 'Offer not found.';
        }

        return view('restaurant_manager/offer/show',compact('offer')); 
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
            return 'Offer not found.';
        }
        $statuses = DB::select('select id, name from statuses');
        $restaurants = DB::table('restaurants')->where('status_id', '!=', 1)
        ->where('manager_id','=',auth()->user()->id)->select('id','name')->get();
        
        return view('restaurant_manager/offer/edit',compact('offer','restaurants','statuses'));

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
        $input = [
            'name' => $request->name,
            'restaurant_id' => $request->restaurant_id,
            'price' => $request->price,
            'details' => $request->details,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status_id' => $request->status_id,
        ];

        $validator = Validator::make($input, [
            'name' => 'required',
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
        ]);
        if($validator->fails()){
            return $validator->errors();       
        }

        $offer = Offer::find($id);
        if ($offer == null ) {
            return 'Offer not found.';
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            $validator = Validator::make($input, [
                'image' => 'file|image|max:5000',
            ]);
            if($validator->fails()){
                return $validator->errors();       
            }
            //delete old image
            Storage::delete($offer->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Offer'.'/'.$fileName;
            $input['image'] = $fileName;
        }
        
   
        Offer::where('id', $id)->update($input);

        return redirect()->intended('web_my_offer');
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

        return redirect()->intended('web_my_offer');
    }
}
