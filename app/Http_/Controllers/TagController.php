<?php
 
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Tag;
use Validator;
use App\Http\Resources\Tags as TagResource;
class TagController extends BaseController
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
        $tags = Tag::all();
    
        return $this->sendResponse($tags->toArray(), 'tags retrieved successfully.');
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
            'name' => 'required|unique:tags'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $tag = Tag::create($input);

        return $this->sendResponse(new TagResource($tag), 'Tag created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $tag = Tag::find($id);
  
        if (is_null($tag)) {
            return $this->sendError('Tag not found.');
        }

        return $this->sendResponse(new TagResource($tag), 'Tag retrieved successfully.');
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
            'name' => 'required | unique:tags,name,'.$request->id
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $tag = Tag::find($id);
        if ($tag == null ) {
            return $this->sendError('Tag not found.');
        }

        $tag->name = $input['name'];
        $tag->save();
   
        return $this->sendResponse(new TagResource($tag), 'Tag updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if ($tag == null ) {
            return $this->sendError('Tag not found.');
        }
        $tag->delete();
   
        return $this->sendResponse($tag->toArray(), 'Tag deleted successfully.');
    }
}
