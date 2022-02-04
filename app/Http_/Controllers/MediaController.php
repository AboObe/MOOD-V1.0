<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Media;
use Validator;
use DB;
use App\Http\Resources\Media as MediaResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class MediaController extends BaseController
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
        $media = DB::table('media');
    
        return $this->sendResponse($media, 'Media retrieved successfully.');
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
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
            'offer_id' => 'numeric',
            'service_id' => 'numeric',
            'image' => 'required|file|image|max:5000',
            'type' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if($input['type'] == 'restaurant'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Restaurant'.'/'.$fileName;
            }

        }elseif($input['type'] == 'service'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Service'.'/'.$fileName;
            }
            
        }elseif($input['type'] == 'offer'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Offer'.'/'.$fileName;
            }  
        }
        $input['path'] = $fileName;
        $media = Media::create($input);

        return $this->sendResponse(new MediaResource($media), 'Media created successfully.');
    
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //just restaurant
        
        
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
            'offer_id' => 'numeric',
            'service_id' => 'numeric',
            'image' => 'required|file|image|max:5000',
            'type' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $media = Media::find($id);
        /**
         * Delete Old Image
         */
        Storage::delete($media->image);
        /**
         * store Image
         */
        $fileName = "" ;
        if($input['type'] == 'restaurant'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Restaurant'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Restaurant'.'/'.$fileName;
            }

        }elseif($input['type'] == 'service'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Service'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Service'.'/'.$fileName;
            }
            
        }elseif($input['type'] == 'offer'){
            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/Offer'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/Offer'.'/'.$fileName;
            }  
        }
        $input['path'] = $fileName;
        Media::where('id', $id)->update($input);

        return $this->sendResponse(new MediaResource($media), 'Media Update successfully.');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $media = Media::find($id);
        Storage::delete($media->image);
        $media->delete();
        return $this->sendResponse(new MediaResource($media), 'Media delete successfully.');
    }
    /**
     * Get All Media to Object
     */
    public function get_media(Request $request){
        if($request->type == 'restaurant'){
            $media = DB::table('media')
            ->where('media.restaurant_id','=',$request->restaurant_id)
            ->where('media.type','=',$request->type)
            ->select('media.*')->get();
        }elseif($request->type == 'service'){
            $media = DB::table('media')
            ->where('media.service_id','=',$request->service_id)
            ->where('media.type','=',$request->type)
            ->select('media.*')->get();
        }elseif($request->type == 'offer'){
            $media = DB::table('media')
            ->where('media.offer_id','=',$request->offer_id)
            ->where('media.type','=',$request->type)
            ->select('media.*')->get();
        }
        return $this->sendResponse($media, 'Get Media successfully.');
    }
}
