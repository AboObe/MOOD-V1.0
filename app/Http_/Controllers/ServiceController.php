<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Service;
use Validator;
use DB;
use App\Http\Resources\Services as ServiceResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ServiceController extends BaseController
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
        $services = DB::table('services')->paginate(50);
    
        return $this->sendResponse($services->toArray(), 'Services retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        $statuses = DB::select('select id , name from statuses');
        $restaurants = DB::select('select id , name from restaurants where status_id <> 1 ');
        $categories = DB::select('select id , name from categories where status_id <> 1');
        $data = [
            'statuses' => $statuses,
            'restaurants' => $restaurants,
            'categories' => $categories
        ];
        return $this->sendResponse($data, 'Statuses, Restaurants and Categories retrieved successfully.'); 
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
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
            'name' => 'required',
            'price' =>'required',
            'category_id' =>'numeric', 
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        /**
         * store Image
         */
        $fileName = null ;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Service'.'/'.$fileName;
        }
        $input['image'] = $fileName;
        $service = Service::create($input);

        return $this->sendResponse(new ServiceResource($service), 'Service created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $service = DB::table('services')
        ->leftjoin('restaurants','restaurants.id','services.restaurant_id')
        ->leftjoin('statuses','statuses.id','services.status_id')
        ->leftjoin('categories','categories.id','services.category_id')
        ->select('services.*','restaurants.name as restaurant_name','statuses.name as status_name','categories.name as category_name')
        ->where('services.id','=',$id)->get();
        if (is_null($service)) {
            return $this->sendError('Service not found.');
        }
        
        return $this->sendResponse($service, 'Service retrieved successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::find($id);
        if ($service == null ) {
            return $this->sendError('Service not found.');
        }
        $statuses = DB::select('select id, name from statuses');
        $restaurants = DB::select('select id , name from restaurants where status_id <> 1');
        $categories = DB::select('select id , name from categories where status_id <> 1');
        $data = [
            'statuses' => $statuses,
            'restaurants' => $restaurants,
            'categories' => $categories,
            'service' => $service
        ];
        return $this->sendResponse($data, 'Restaurant, statuses, categories and my service retrieved successfully.');
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
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
            'name' => 'required',
            'price' =>'required',
            'category_id' =>'numeric',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $service = Service::find($id);
        if ($service == null ) {
            return $this->sendError('Service not found.');
        }
        /**
         * store Image
         */
        $fileName = null ;
        if ($request->hasFile('image')) {
            
            //delete old image
            Storage::delete($service->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Service'.'/'.$fileName;
        }
        //return ($fileName);
        $input['image'] = $fileName;
   
        Service::where('id', $id)->update($input);

   
        return $this->sendResponse(new ServiceResource($service), 'Service updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $service = Service::find($id);
        if ($service == null ) {
            return $this->sendError('Service not found.');
        }
        $input['status_id'] = 1; 
        Service::where('id', $id)->update($input);
        /*
        Storage::delete($service->image);
        $service->delete();
        */
        return $this->sendResponse([], 'Service deleted successfully.');
    }
}
