<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Status;
use Validator;

class StatusController extends Controller
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
        $statuses = Status::all();
        $count = $statuses->count();

        return view('admin/status/index',compact('statuses','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/status/create');
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
            return $validator->errors();       
        }
        
        $status = Status::create($input);

        return redirect()->intended('web_status');
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
            return 'Status not found.';
        }

        return view('admin/status/show',compact('status'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = Status::find($id);
  
        if (is_null($status)) {
            return 'Status not found.';
        }

        return view('admin/status/edit',compact('status'));
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
            'name' => 'required | unique:statuses,name,'.$id
        ]);

        if($validator->fails()){
            return $validator->errors();       
        }

        $status = Status::find($id);
        if ($status == null ) {
            return 'Status not found.';
        }

        $status->name = $input['name'];
        $status->save();
   
        return redirect()->intended('web_status');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        Status::where('id', $id)->delete();
        return redirect()->intended('web_status');
    }
}
