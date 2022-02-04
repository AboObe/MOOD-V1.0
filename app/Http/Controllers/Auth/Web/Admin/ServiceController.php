<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Service;
use Validator;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{ 
    public function __construct()
    {
        $this->middleware(['auth','Admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = DB::table('services')
        ->leftjoin('restaurants','restaurants.id','services.restaurant_id')
        ->leftjoin('statuses','statuses.id','services.status_id')
        ->leftjoin('categories','categories.id','services.category_id')
        ->select('services.*','restaurants.name as restaurant_name','statuses.name as status_name','categories.name as category_name')
        ->get();
        $count = $services->count();

        return view('admin/service/index',compact('services','count'));
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
        
        return view('admin/service/create',compact('restaurants','statuses','categories'));
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
            'name' => 'required',
            'price' =>'required',
            'category_id' =>'numeric', 
        ]);

        if($validator->fails()){
            return $validator->errors();       
        }
        /**
         * store Image
         */ 
        $fileName = null ;
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
            Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Service'.'/'.$fileName;
            $input['image'] =  $fileName;
        }
        
        $service = Service::create($input);

        return redirect()->intended('web_service');
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
        ->where('services.id','=',$id)->first();
        if (is_null($service)) {
            return 'Service not found.';
        }
        
        return view('admin/service/show',compact('service'));
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
            return 'Service not found.';
        }
        
        $statuses = DB::select('select id , name from statuses');
        $restaurants = DB::select('select id , name from restaurants where status_id <> 1 ');
        $categories = DB::select('select id , name from categories where status_id <> 1');
        
        return view('admin/service/edit',compact('restaurants','statuses','categories','service'));
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
            'price' => $request->price,
            'content' => $request->content,
            'status_id' => $request->status_id,
            'restaurant_id' => $request->restaurant_id,
            'category_id' => $request->category_id,
        ];
        
        $validator = Validator::make($input, [
            'restaurant_id' => 'numeric',
            'status_id' => 'numeric',
            'name' => 'required',
            'price' =>'required',
            'category_id' =>'numeric',
        ]);
        if($validator->fails()){
            return $validator->errors();       
        }


        $service = Service::find($id);
        if ($service == null ) {
            return 'Service not found.';
        }
        /**
         * store Image
         */
        $fileName = null ;
        if ($request->hasFile('image')) {
            $validator = Validator::make($input, [
            'image' => 'file|image|max:5000',
            ]);

            if($validator->fails()){
                return $validator->errors();       
            }
            //delete old image
            Storage::delete($service->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Service'.'/'.$fileName;
            $input['image'] = $fileName;
        }
    
        
   
        Service::where('id', $id)->update($input);
        return view('admin/service/show',compact('service'));
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
            return 'Service not found.';
        }
        $input['status_id'] = 1; 
        Service::where('id', $id)->update($input);
    }
}
