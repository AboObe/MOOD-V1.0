<?php
 
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Status;
use Validator;
use App\Http\Resources\Statuses as StatusResource;
class StatusController extends BaseController
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
        $statuses = Status::all();
    
        return $this->sendResponse($statuses->toArray(), 'Statuses retrieved successfully.');
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
            'name' => 'required|unique:statuses'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $status = Status::create($input);

        return $this->sendResponse(new StatusResource($status), 'Status created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $status = Status::find($id);
  
        if (is_null($status)) {
            return $this->sendError('Status not found.');
        }

        return $this->sendResponse(new StatusResource($status), 'Status retrieved successfully.');
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
            'name' => 'required | unique:statuses,name,'.$request->id
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $status = Status::find($id);
        if ($status == null ) {
            return $this->sendError('Status not found.');
        }

        $status->name = $input['name'];
        $status->save();
   
        return $this->sendResponse(new StatusResource($status), 'Status updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $status = Status::find($id);
        if ($status == null ) {
            return $this->sendError('Status not found.');
        }
        $status->delete();
   
        return $this->sendResponse($status->toArray(), 'Status deleted successfully.');
    }
}
