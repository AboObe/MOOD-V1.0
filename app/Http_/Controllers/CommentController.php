<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Comment;
use Validator;
use App\Http\Resources\Comments as CommentResource;
use Illuminate\Support\Facades\Input;

class CommentController extends BaseController
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
        $comments = Comment::all();
    
        return $this->sendResponse($comments->toArray(), 'Categories retrieved successfully.');
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
            'comment' => 'required',
            'user_id' => 'required',
            'review_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $comment = Comment::create($input);

        return $this->sendResponse(new CommentResource($comment), 'Comment created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return $this->sendError('Comment not found.');
        }
        
        return $this->sendResponse(new CommentResource($comment), 'Comment retrieved successfully.');
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
            'comment' => 'required',
            'user_id' => 'required',
            'review_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $comment = Comment::find($id);
        if ($comment == null ) {
            return $this->sendError('Comment not found.');
        }
        
        $comment->comment = $input['comment'];
        $comment->user_id = $input['user_id'];
        $comment->review_id = $input['review_id'];
        $comment->save();
   
        return $this->sendResponse(new CommentResource($comment), 'Comment updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment == null ) {
            return $this->sendError('Comment not found.');
        }
        $comment->delete();
        
        return $this->sendResponse([], 'Comment deleted successfully.');
    }


    /**
     * Store Image
     */
    private function storeImage($comment)
    {
        if (request()->has('image')) {
            //$image = request()->file('pictures');
            //$name=time() . '.' .$image->getClientOriginalName();
            $comment->update([
                'image' => request()->image->store('uploads', 'public'),
            ]);
            //$image->save();
        }
    }
}
