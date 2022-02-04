<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
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
use App\Http\Resources\Users as UserResource;


class ProfileController extends BaseController
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }



 
    public function edit(Request $request)
    {
        $user = User::find($request->id);
        if ($user == null ) {
            return $this->sendError('User not found.',404);
        }

        return $this->sendResponse(new UserResource($user),'Get User Profile Successfully');

    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $request->id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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
        


        $validator = Validator::make($input, [
            'email' => 'required | unique:users,email,'.$request->id,
            'type' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),409);       
        }

        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('photo')) {
            $validator = Validator::make($input, [
                'photo' => 'file|image|max:5000',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());       
            }
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


        user::where('id', $request->id)->update($input);
        $user = User::find($request->id);

        return $this->sendResponse(new UserResource($user), 'User updated successfully.');
    }
}
