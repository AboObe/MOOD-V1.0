<?php
 
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Validator;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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
        $categories = Category::all();
        $count = $categories->count();

        return view('admin/category/index',compact('categories','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::table('statuses')->select('id','name')->get();
        return view('admin/category/create',compact('statuses'));
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
            'email' => 'required',
        ]);
   
        if($validator->fails()){
            return $validator->errors();       
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
            Storage::disk('local')->put('public/Category'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Category'.'/'.$fileName;
            $input['image'] = $fileName;
        }

        
        
        $category = Category::create($input);
        
        return redirect()->intended('web_category');
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
        ->select('categories.*','statuses.name as status_name')->first();
        if (is_null($category)) {
            return 'Category not found.';
        }

        return view('admin/category/show',compact('category'));
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
            return 'Category not found.';
        }
        $statuses = DB::select('select id, name from statuses');
        
        return view('admin/category/edit',compact('statuses','category'));
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
            return 'Category not found.';
        }
        
        $input = [
            'name' => $request->name,
            'status_id' => $request->status_id,
            'image' => $request->image,
        ];
        $validator = Validator::make($input, [
            'name' => 'required | unique:categories,name,'.$id,
            'status_id' => 'numeric',
        ]);
        if($validator->fails()){
            return $validator->errors();       
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
            Storage::delete($category->image);
            //add new image
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/Category'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/Category'.'/'.$fileName;
            $input["image" ] = $fileName;
        }

        Category::where('id', $id)->update($input);
        
        $category->save();

        return redirect()->intended('web_category');
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
            return 'Category not found.';
        }
        $input['status_id'] = 1; 
        Category::where('id', $id)->update($input);

        return redirect()->intended('web_category');
    }
}
