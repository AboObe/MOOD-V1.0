<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use Validator;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
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
        $users = User::all();
        $count = $users->count();

        return view('admin/user/index',compact('users','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = DB::table('statuses')->get();
        return view('admin/user/create',compact('statuses'));
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
        $this->validate($request, [
            'email' => 'required|unique:users',
            'photo' => 'file|image|max:5000',
            'type' => 'required',
        ]);
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/User'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/User'.'/'.$fileName;
            $input['photo'] = $fileName;
        }

        $input['name'] = trim($request->input('name'));
        $input['email'] = strtolower($request->input('email'));
        $input['password'] = bcrypt($request->input('password'));
        $input['email_verification_token'] = Str::random(32);
      

        $user = User::create($input);

        \Mail::to($user->email)->send(new VerificationEmail($user));

        session()->flash('message', 'Please check your email to activate your account');
        
        
        return redirect()->intended('web_user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')
                ->leftjoin('statuses','statuses.id','=','users.status_id')
                ->select('users.*','statuses.name as status_name')
                ->where('users.id','=',$id)->first();
        return view('admin/user/show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table('users')
                ->leftjoin('statuses','statuses.id','=','users.status_id')
                ->select('users.*','statuses.name as status_name')
                ->where('users.id','=',$id)->first();
        $statuses = DB::table('statuses')->get();
        return view('admin/user/edit',compact('user','statuses'));
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
        $user = User::find($id);
        if ($user == null ) {
            return response('User not found.');
        }
        
        $input = [
            'name' => trim($request->name),
            'type' => $request->type == null ? $user->type : $request->type ,
            'email' => $request->email == null ? $user->email : strtolower($request->email),
            'phone' => $request->phone,
            'address' => $request->address,
            'status_id' => $request->status_id,
            'dob' => Carbon::parse($request->dob),
            'emirate' => $request->emirate,
            'gender' => $request->gender,
        ];
        /**
        * Password
        */
        if(!($request->password == null || $request->password == ""))
            $input['password'] = bcrypt($request->password);



        $this->validate($request, [
            'email' => 'required | unique:users,email,'.$id,
            'type' => 'required',
            'photo' => 'file|image|max:5000',
        ]);

        $user = user::find($id);
        if ($user == null ) {
            return $this->sendError('user not found.');
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('photo')) {
            
            //delete old image
            Storage::delete($user->photo);
            //add new image
            $image      = $request->file('photo');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/user'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/user'.'/'.$fileName;
            $input["photo" ] = $fileName;
        }


        user::where('id', $id)->update($input);
        
        $user->save();

        if($request->code == "profile")
            return back();
        else
            return redirect()->intended('web_user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user == null ) {
            return $this->sendError('user not found.');
        }
        $input['status_id'] = 1; 
        User::where('id', $id)->update($input);
        return redirect()->intended('web_user');
    }
}
