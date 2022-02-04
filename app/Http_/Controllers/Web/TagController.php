<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tag;
use Validator;

class TagController extends Controller
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
        $tags = Tag::all();
        $count = $tags->count();

        return view('admin/tag/index',compact('tags','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/tag/create');
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
            return $validator->errors();       
        }
        
        $Tag = Tag::create($input);

        return redirect()->intended('web_tag');
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
            return 'Tag not found.';
        }

        return view('admin/tag/show',compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
  
        if (is_null($tag)) {
            return 'Tag not found.';
        }

        return view('admin/tag/edit',compact('tag'));
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
            'name' => 'required | unique:tags,name,'.$id
        ]);

        if($validator->fails()){
            return $validator->errors();       
        }

        $tag = Tag::find($id);
        if ($tag == null ) {
            return 'Tag not found.';
        }

        $tag->name = $input['name'];
        $tag->save();
   
        return redirect()->intended('web_tag');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::where('id', $id)->delete();
        return redirect()->intended('web_tag');
    }
}
