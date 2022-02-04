<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Restaurant;
use App\TagRestaurant;
use App\RestaurantCategory;
use Validator;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurantes = DB::table('restaurants')->get();
        $count = $restaurantes->count();

        return view('admin/restaurant/index',compact('restaurantes','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::select('select id , name from statuses');
        $tags = DB::select('select id , name from tags');
        $categories = DB::select('select id , name from categories');
        $users = DB::table('users')->where('type', '=','restaurant_manager')
        ->select('id','name')->get();
        return view('admin/restaurant/create',compact('tags','statuses','users','categories')); 
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
            'status_id' => 'numeric',
            'city' => 'required', 
            'email' => 'email',  
        ]);

        if($validator->fails()){
            return $validator->errors();       
        } 
        /**
         * store Images
         */
         $images=[];
         $i = 0;
         if(! is_null(request('files')))
        {
            $photos=request()->file('files');
            foreach ($photos as $photo)
             {
                $fileName   = time() . auth()->user()->id  .  $i  . '.' .  $photo->getClientOriginalExtension();
                $i +=1 ;
                $img = Image::make($photo->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Restaurant'.'/'.$fileName;
                array_push($images, $fileName);
              } 
            $input['photos'] = json_encode($images);         
        }
        /**
         * store Profile Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Restaurant'.'/'.$fileName;
            $input['image'] = $fileName;
        }

        //return $input;
        
        $restaurant = Restaurant::create($input);
        
        foreach($input['new_tags'] as $tag)
        {
            TagRestaurant::create(['restaurant_id'=>$restaurant->id,'tag_id'=>$tag]);
        }

        foreach($input['new_categories'] as $category)
        {
            RestaurantCategory::create(['restaurant_id'=>$restaurant->id,'category_id'=>$category]);
        }

        return redirect()->intended('web_restaurant');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $restaurant = DB::table('restaurants')
        ->leftjoin('statuses','restaurants.status_id','statuses.id')
        ->leftjoin('users','users.id','restaurants.manager_id')
        ->where('restaurants.id','=',$id)
        ->select('restaurants.*','statuses.name as status_name','users.name as user_name')->first();
      

        if (is_null($restaurant)) {
            return 'Restaurant not found.';
        }

        $photos = json_decode($restaurant->photos);
        
        
        $tags = DB::table('tags')
        ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
        ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('tags.name')->get();

        $categories = DB::table('categories')
        ->leftjoin('restaurant_categories','categories.id','restaurant_categories.category_id')
        ->leftjoin('restaurants','restaurant_categories.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('categories.name')->get();

        $offers = DB::table('offers')
        ->where('restaurant_id','=',$id)
        ->select('offers.name')->get();

        $campaigns = DB::table('campaigns')
        ->leftjoin('restaurant_campaigns','campaigns.id','restaurant_campaigns.campaign_id')
        ->leftjoin('restaurants','restaurant_campaigns.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->where('campaigns.status_id','!=',1)
        ->select('campaigns.name')->get();
        
        return view('admin/restaurant/show',compact('restaurant','tags','categories','offers','campaigns','photos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $restaurant = Restaurant::find($id);
        if ($restaurant == null ) {
            return 'Restaurant not found.';
        }

        $photos = json_decode($restaurant->photos);
        
        
        $restaurant_tags = DB::table('tags')
        ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
        ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('tags.name','tags.id')->get();

        $restaurant_categories = DB::table('categories')
        ->leftjoin('restaurant_categories','categories.id','restaurant_categories.category_id')
        ->leftjoin('restaurants','restaurant_categories.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$id)
        ->select('categories.name','categories.id')->get();

        $offers = DB::table('offers')
        ->where('restaurant_id','=',$id)
        ->select('offers.name','offers.id')->get();





        $statuses = DB::select('select id, name from statuses');
        $tags = DB::select('select id , name from tags');
        $users = DB::select('select id , name from users where type = "restaurant_manager"');
        $categories = DB::select('select id , name from categories');

        return view('admin/restaurant/edit',compact('restaurant','statuses','restaurant_tags','tags','users','restaurant_categories','categories','photos'));
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
        $restaurant = Restaurant::find($id);
        if ($restaurant == null ) {
            return 'Restaurant not found.';
        }

        $input = [
            'name' => $request->name,
            'city' => $request->city,
            'description' => $request->description,
            'opening_hours' => $request->opening_hours,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'snapchat' => $request->snapchat,
            'whatsapp' => $request->whatsapp,
            'youtube' => $request->youtube,
            'website' => $request->website,
            'qr' => $request->qr,
            'pin' => $request->pin,
            'status_id' => $request->status_id,
            'manager_id' => $request->manager_id,
            'is_featured' => $request->is_featured
        ];

        $validator = Validator::make($input, [
            'name' => 'required',
            'status_id' => 'numeric',
            'city' => 'required',
            'email' => 'email', 
        ]);
        if($validator->fails()){
            return $validator->errors();       
        }

        $restaurant = Restaurant::find($id);
        if ($restaurant == null ) {
            return 'Restaurant not found.';
        }
        /**
         * store Images
         */
         $images=[];
         $i = 0;
         if(! is_null(request('files')))
        {
            $photos=request()->file('files');
            foreach ($photos as $photo)
             {
                $fileName   = time() . auth()->user()->id  .  $i  . '.' .  $photo->getClientOriginalExtension();
                $i +=1 ;
                $img = Image::make($photo->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Restaurant'.'/'.$fileName;
                array_push($images, $fileName);
              } 
            $input['photos'] = json_encode($images);         
        }
        /**
         * store Profile Image
         */
        $fileName = '';
        if ($request->hasFile('image')) {
            $validator = Validator::make($input, [
                'image' => 'file|image|max:5000',
            ]);
            if($validator->fails()){
                return $validator->errors();       
            }
            //delete old image
            Storage::delete($restaurant->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = 'profile' .time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Restaurant'.'/'.$fileName;
            $input["image"] = $fileName; 
        }
        Restaurant::where('id', $id)->update($input);
        /*
        * Update Tag_Restaurant
        */
        $new_tags_without_null = array_filter($request->new_tags);

        $create_tags = 
          ( $request->old_tags == null ? 
              $new_tags_without_null : 
                  ($new_tags_without_null == null ?
                      null : array_diff($new_tags_without_null,$request->old_tags)));

        $delete_tags = 
          ( $new_tags_without_null == null ?
              $request->old_tags : 
                  ($request->old_tags == null ? 
                    null : array_diff($request->old_tags,$new_tags_without_null)));
        
        if($delete_tags != null){
          foreach($delete_tags as $del)
          {
          $tag_id = (int)$del;
          TagRestaurant::where('tag_id', $tag_id)->where('restaurant_id',$id)->delete();
          }
        }
        if($create_tags != null){
          foreach($create_tags as $cre) {
            $tag_id = (int)$cre;
            TagRestaurant::create(["tag_id"=>$tag_id,"restaurant_id"=>$id]);
          }
        }
        /*
        * Update Categories_Restaurant
        */
        $new_categories_without_null = array_filter($request->new_categories);

        $create_categories = 
          ( $request->old_categories == null ? 
              $new_categories_without_null : 
                  ($new_categories_without_null == null ?
                      null : array_diff($new_categories_without_null,$request->old_categories)));

        $delete_categories = 
          ( $new_categories_without_null == null ?
              $request->old_categories : 
                  ($request->old_categories == null ? 
                    null : array_diff($request->old_categories,$new_categories_without_null)));

        if($delete_categories != null){
          foreach($delete_categories as $del)
          {
            $category_id = (int)$del;
            RestaurantCategory::where('category_id', $category_id)->where('restaurant_id',$id)->delete();
          }
        }
        if($create_categories != null){
          foreach($create_categories as $cre) {
            $category_id = (int)$cre;
            RestaurantCategory::create(["category_id"=>$category_id,"restaurant_id"=>$id]);
          }
        }

        if(auth()->user()->type == 'admin')
          return redirect()->intended('web_restaurant');
        else
          return redirect()->intended('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);
        if ($restaurant == null ) {
            return 'Restaurant not found.';
        }
        $input['status_id'] = 1; 
        Restaurant::where('id', $id)->update($input);
    }
    /**
     * Show My Restaurant for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function my_restaurant($manager_id)
    {
        $restaurant = DB::table('restaurants')
                    ->where('manager_id','=',$manager_id)->first();

        if ($restaurant == null ) {
            return 'Restaurant not found.';
        }

        $photos = json_decode($restaurant->photos);
        
        
        $restaurant_tags = DB::table('tags')
        ->leftjoin('tag_restaurants','tags.id','tag_restaurants.tag_id')
        ->leftjoin('restaurants','tag_restaurants.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$restaurant->id)
        ->select('tags.name','tags.id')->get();

        $restaurant_categories = DB::table('categories')
        ->leftjoin('restaurant_categories','categories.id','restaurant_categories.category_id')
        ->leftjoin('restaurants','restaurant_categories.restaurant_id','restaurants.id')
        ->where('restaurants.id','=',$restaurant->id)
        ->select('categories.name','categories.id')->get();

        $offers = DB::table('offers')
        ->where('restaurant_id','=',$restaurant->id)
        ->select('offers.name','offers.id')->get();


        $statuses = DB::select('select id, name from statuses');
        $tags = DB::select('select id , name from tags');
        $categories = DB::select('select id , name from categories');


        return view('restaurant_manager/restaurant/edit_my_rest',compact('restaurant','statuses','restaurant_tags','tags','restaurant_categories','categories','photos'));
    } 

    /**
    *
    *
    *  DELETE IMAGES FROM GALLERY RESTAURANT
    *
    **/
    public function delete_photo(Request $request){
      
      $restaurant = Restaurant::find($request->restaurant_id);

      $photos = json_decode($restaurant->photos);

      $images = [];
      foreach($photos as $photo) {
         if($photo != $request->photo){
            array_push($images, $photo);
         }
      }
      $input['photos'] = json_encode($images);
      $restaurant->update($input);
    }
}
