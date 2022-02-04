<?php

namespace App\Http\Controllers\Web;

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

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth',"Admin" || "RestaurantManager"]);
    }
    /**
     * Show the Profile for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        if( Auth::user()->type == "admin")
            return view('admin/user/profile',compact('user'));
        else
            return view('restaurant_manager/user/profile',compact('user'));
            
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
        $user = User::find($request->id);
        if ($user == null ) {
            return $this->sendError('User not found.',404);
        }
        
        $input = [
            'name' => trim($request->name),
            'type' => $request->type == null ? $user->type : $request->type ,
            'email' => $request->email == null ? $user->email : strtolower($request->email),
            'phone' => $request->phone,
            'address' => $request->address,
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
            'email' => 'required | unique:users,email,'.$request->id,
            'type' => 'required',
            'photo' => 'file|image|max:5000',
        ]);
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
}
