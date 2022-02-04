<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Category;
use Validator;
use DB;
use App\Http\Resources\Categories as CategoryResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BaseController
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
        $categories = Category::all();
    
        return $this->sendResponse($categories->toArray(), 'Categories retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        $status = DB::select('select id , name from statuses');
        return $this->sendResponse($status, 'Statuses retrieved successfully.'); 
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
            'name' => 'required|unique:categories',
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Category'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Category'.'/'.$fileName;
        }

        $input['image'] = $fileName;
        $category = Category::create($input);


        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $category = DB::table('categories')
        ->leftjoin('statuses','statuses.id','categories.status_id')
        ->where('categories.id','=',$id)
        ->select('categories.*','statuses.name as status_name')->get();
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }
        
        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category == null ) {
            return $this->sendError('Category not found.');
        }
        $statuses = DB::select('select id, name from statuses');
        $data=[
            'statuses'=> $statuses,
            'category' => $category
        ];
        return $this->sendResponse($data, 'Category and statuses retrieved successfully.');
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
        $category = Category::find($id);
        if ($category == null ) {
            return $this->sendError('Category not found.');
        }
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required | unique:categories,name,'.$id,
            'status_id' => 'numeric',
            'image' => 'file|image|max:5000',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $category = Category::find($id);
        if ($category == null ) {
            return $this->sendError('Category not found.');
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('image')) {
            //delete old image
            Storage::delete($category->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Category'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Category'.'/'.$fileName;
        }

        Category::where('id', $id)->update($input);
        
        $category->save();

        $category = Category::find($id);
   
        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category == null ) {
            return $this->sendError('Category not found.');
        }
        $input['status_id'] = 1; 
        Category::where('id', $id)->update($input);
        /*
        Storage::delete($category->image);
        $category->delete();
        */
        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
